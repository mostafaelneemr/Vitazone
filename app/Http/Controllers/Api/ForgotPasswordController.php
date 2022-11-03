<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user  = User::where('email',$request->email)->first();
        if (empty($user)){
            return response()->json(['status'=>false,'message'=>__('This email do not match our records')],422);
        }
        $code = rand(100000, 999999).$user->id;
        $user->update(['password_code'=>$code]);
        \Mail::to($request->email)->send(  new ForgetPassword($code,$user));
        return response()->json(['status'=>true,'message'=>__('we send code to your email ')],200);
    }
    protected function sendResetLinkResponse(Request $request, $response)
    {
        $request->validate(['email' => 'required|email']);
        return response()->json(['status'=>true,'message'=>trans($response)],200);
    }
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['status'=>false,'message'=>trans($response)],200);

    }
}
