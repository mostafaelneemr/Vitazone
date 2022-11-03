<?php

namespace App\Http\Controllers\Api;

use App\Mail\UserVerification;
use Illuminate\Http\Request;
use App\User;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'nullable|string|email',
            'phone' => 'nullable',
        ]);

        if (isset($request->email)) {
            $user = User::where('email', $request->email)->first();
        } elseif (isset($request->phone)) {
            $user = User::where('phone', $request->phone)->first();
        }

        if (!$user)
            return response()->json([
                'success' => false,
                'message' => trans('messages.We can not find a user with that e-mail address')
            ], 404);

        if (isset($request->email)) {
            $content = translate('Hi. this is your virification code to reset your password');
            $user->password_code = rand(100000, 999999);
            $user->save();
            try {
                Mail::to($request->email)->queue(new UserVerification($user->password_code, $user, $content));
            } catch (\Exception $e) {
            }
        } elseif (isset($request->phone)) {
            $user->password_code = rand(100000, 999999);
            $user->save();
            sendSMS($user->phone, env('APP_NAME'), $user->password_code . ' is your verification Code');
        }
        return response()->json([
            'success' => true,
            'code' => $user->password_code,
            'message' => trans('messages.Verification code sent to you')
        ], 200);
    }
}
