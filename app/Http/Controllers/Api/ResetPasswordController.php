<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    public function resetPass(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'password' => 'required|min:6',
        ]);
        if (isset($request->email)) {
            $user = User::where('password_code', $request->code)->where('email', $request->email)->first();
        } elseif (isset($request->phone)) {
            $user = User::where('password_code', $request->code)->where('phone', $request->phone)->first();
        }

        if (empty($user)) {
            return response()->json(['status' => false, 'message' => __('invalid code or email')], 422);
        }
        $theRequest['password'] = Hash::make($request->password);
        $theRequest['password_code'] = null;
        $user->update($theRequest);
        if (isset($request->email)) {
            $credentials = request(['email', 'password']);
        } elseif (isset($request->phone)) {
            $credentials = request(['phone', 'password']);
        }
        if (!Auth::attempt($credentials))
            return response()->json(['message' => trans('messages.Unauthorized')], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $auth = new AuthController();
        return $auth->loginSuccess($tokenResult, $user);
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['status' => true, 'message' => trans($response)], 200);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['status' => true, 'message' => trans($response)], 200);
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
