<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;

class OTPVerificationController extends Controller
{

    public function verify_phone(Request $request)
    {
        $this->validate( $request, [
            'verification_code' => 'required'
        ] );
        $user = Auth::user();
        if ($user->verification_code == $request->verification_code) {
            $user->email_verified_at = date( 'Y-m-d h:m:s' );
            $user->save();
            return response()->json( [
                'message' => trans('messages.Your phone number has been verified successfully')
            ], 200 );
        } else {
            return response()->json( [
                'message' => 'Invalid Code'
            ], 422 );
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function resend_verification_code(Request $request)
    {
        $request->validate( ['phone' => 'required'] );
        $user = User::where( 'phone', $request->phone )->first();
        if (empty( $user )) {
            return response()->json( [
                'message' => trans('messages.your phone number do not match our records')
            ], 422 );
        }
        $user->verification_code = rand( 100000, 999999 );
        $user->save();

        sendSMS( $user->phone, env( "APP_NAME" ), $user->verification_code . ' is your verification Code for ' . env( 'APP_NAME' ) );

        return response()->json( [
            'message' => trans('messages.Verification Code  has been sent successfully  to your phone number')
        ], 200 );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function reset_password_with_code(Request $request)
    {
        $request->validate( [
            'phone' => 'required',
            'verification_code' => 'required',
            'password' => 'required|confirmed'
        ] );
        if (($user = User::where( 'phone', $request->phone )->where( 'verification_code', $request->verification_code )->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password = Hash::make( $request->password );
                $user->email_verified_at = date( 'Y-m-d h:m:s' );
                $user->save();
                $tokenResult = $user->createToken( 'Personal Access Token' );
                $auth = new AuthController();
                return $auth->loginSuccess( $tokenResult, $user );
            } else {
                return response()->json( [
                    'message' => trans(`messages.Password and confirm password didn't match`)
                ], 422 );

            }
        } else {
            return response()->json( [
                'message' => trans('messages.Verification code mismatch')
            ], 422 );
        }
    }

    /**
     * @param User $user
     * @return void
     */

    public function send_code(Request $request)
    {
        $request->validate( ['phone' => 'required'] );
        $user = User::where( 'phone', $request->phone )->first();
        if (empty( $user )) {
            $request->phone = '+2' . $request->phone;
            if (empty($user = User::where( 'phone', $request->phone )->first())) {
                return response()->json( [
                    'success' =>  false,
                    'message' =>  trans('messages.your phone number do not match our records')
                ] );
            }
        }
        $user->verification_code = rand( 100000, 999999 );
        $user->save();

        sendSMS( $user->phone, env( "APP_NAME" ), $user->verification_code . ' is your verification Code for ' . env( 'APP_NAME' ) );

        return response()->json( [
            'success' =>  true,
            'message' =>  trans('messages.Verification Code  has been sent successfully  to your phone number')
        ], 200 );
    }

}
