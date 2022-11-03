<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api;

use App\Mail\ForgetPassword;
use App\Mail\InvoiceEmailManager;
use App\Mail\UserVerification;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Notifications\EmailVerificationNotification;
use App\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required_if:phone,null|string|email|unique:users,email',
                'phone' => 'required_if:email,null|unique:users,phone',
                'password' => 'required|string|min:6'
            ],
            [
                'name.required' => trans('messages.Name is required.'),
                'email.unique' => trans('messages.The email has already been taken.'),
                'email.required' => trans('messages.Email is required.'),
                'phone.required' => trans('messages.Phone is required.'),
                'phone.unique' => trans('messages.The phone has already been taken.'),
                'password.required' => trans('messages.Password is required.'),
                'password.min' => trans('messages.The password must be at least 6 characters.'),
            ]
        );
        if (isset($request->email)) {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'verification_code' => rand(100000, 999999)
            ]);
            $user->save();
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            } else {
                $content = translate('Hi. this is your virification code to active your account');
                try {
                    Mail::to($request->email)->queue(new UserVerification($user->verification_code, $user, $content));
                } catch (\Exception $th) {
                    //throw $th;
                }
            }
            $user->save();
            if (isset($request->device_token)) {
                $user->device_token = $request->device_token;
            }
            $customer = new Customer;
            $customer->user_id = $user->id;
            if ($customer->save()) {
                $data['success'] = true;
                $data['code'] = $user->verification_code;
                $data['message'] = trans('messages.Verification mail send to your email please check it.');
            } else {
                $data['success'] = false;
                $data['message'] = trans('messages.Somthing went wrong.');
            }
            return response()->json($data);
        } elseif (isset($request->phone)) {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'verification_code' => rand(100000, 999999)
            ]);

            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();
            if (isset($request->device_token)) {
                $user->device_token = $request->device_token;
            }
            $user->save();
            sendSMS($user->phone, env('APP_NAME'), $user->verification_code . ' is your verification Code');
            $customer = new Customer;
            $customer->user_id = $user->id;
            if ($customer->save()) {
                $data['success'] = true;
                $data['code'] = $user->verification_code;
                $data['message'] = trans('messages.Verification code send to your phone please check it.');
            } else {
                $data['success'] = false;
                $data['message'] = trans('messages.Somthing went wrong.');
            }
            return response()->json($data);
        }
    }
    public function activeAccount(Request $request)
    {
        if (isset($request->email)) {
            $user = User::where(['email' => $request->email, 'verification_code' => $request->verification_code])->first();
        } elseif (isset($request->phone)) {
            $user = User::where(['phone' => $request->phone, 'verification_code' => $request->verification_code])->first();
        }
        if ($user != null) {
            $user->email_verified_at = Carbon::now();
            $user->save();
            $phone = new Phone;
            $phone->user_id = $user->id;
            $phone->phone = $request->phone;
            $phone->v_code = 1234;
            $phone->status = 'actived';
            $phone->save();
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $user);
        } else {
            return response()->json(['status' => false, 'message' => __('invalid code or email or phone')], 422);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'nullable|string|email',
            'phone' => 'nullable',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        if (isset($request->email)) {
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json(['success' => false, 'message' => trans('messages.Invalid email or password')]);
            }
        } elseif (isset($request->phone)) {
            $credentials = request(['phone', 'password']);
            if (!Auth::attempt($credentials)) {
                $request->phone = '+2' . $request->phone;
                $credentials = ["phone" => $request->phone, "password" => $request->password];
                // return $credentials;
                if (!Auth::attempt($credentials)) {
                    return response()->json(['success' => false, 'message' => trans('messages.Invalid phone or password')]);
                }
            }
        }

        $user = $request->user();
        if ($user->email_verified_at == null) {
            return response()->json([
                'success' => false,
                'message' => trans('messages.Account_not_activated')
            ]);
        }
        if (isset($request->device_token)) {
            $user->device_token = $request->device_token;
            $user->save();
        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    public function user(Request $request)
    {
        return response()->json(['data' => $request->user()]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => trans('messages.Successfully logged out')
        ]);
    }

    public function socialLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'name' => 'required'
        ]);
        if (User::where('email', $request->email)->first() != null) {
            $user = User::where('email', $request->email)->first();
        } else {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'provider_id' => $request->provider,
                'email_verified_at' => Carbon::now()
            ]);
            if (isset($request->device_token)) {
                $user->device_token = $request->device_token;
                $user->save();
            }
            $user->save();
            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();
        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    public function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        return response()->json([
            'success' => true,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => $user->avatar_original,
                'address' => $user->address,
                'country'  => $user->country,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
                'phone' => $user->phone
            ]
        ]);
    }
    public function update_profile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'nullable|confirmed',
            'email' => 'required_if:phone,null|email|unique:users,email,' . $user->id
        ]);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['errors'] = $validator->errors();
            return response()->json($data);
        }
        $theRequest = $request->all();
        // if (isset($theRequest['email']) && $theRequest['email'] != $user->email) {
        //     if (isset($theRequest['code'])) {
        //         if ($theRequest['code'] != $user->verification_code) {
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => trans('messages.Not valid code')
        //             ]);
        //         }
        //     } else {
        //         $content = translate('Hi. this is your virification code to verify your mail');
        //         $user->verification_code = rand(100000, 999999);
        //         $user->save();
        //         try {
        //             Mail::to($request->email)->queue(new UserVerification($user->verification_code, $user, $content));
        //         } catch (\Exception $e) {
        //         }
        //         return response()->json([
        //             'success' => true,
        //             'status' => 1,
        //             'code' => $user->verification_code,
        //             'message' => trans('messages.We send to you virification code to active email')
        //         ]);
        //     }
        // }
        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $theRequest['password'] = Hash::make($request->new_password);
        }
        if ($request->hasFile('avatar_original')) {
            $theRequest['avatar_original'] = $request->avatar_original->store('uploads/users');
        }
        if ($request->hasFile('avatar')) {
            $theRequest['avatar'] = $request->avatar->store('uploads/users');
        }
        if ($user->update($theRequest)) {
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccessupdat($tokenResult, $user, 2);
        }
        return response()->json([
            'success' => false,
            'message' => trans('messages.Sorry! Something went wrong.')
        ]);
    }

    public function update_avatar(Request $request)
    {
        $user = Auth::user();
        $theRequest = $request->all();
        if ($request->hasFile('avatar_original')) {
            $theRequest['avatar_original'] = $request->avatar_original->store('uploads/users');
        }
        if ($request->hasFile('avatar')) {
            $theRequest['avatar'] = $request->avatar->store('uploads/users');
        }
        if ($user->update($theRequest)) {
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccessupdat($tokenResult, $user, 2);
        }
    }

    private function loginSuccessupdat($tokenResult, $user, $status)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        return response()->json([
            'success' => true,
            'status' => $status,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => $user->avatar_original,
                'address' => $user->address,
                'country'  => $user->country,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
                'phone' => $user->phone
            ]
        ]);
    }

    public function change_passowrd(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'password' => 'nullable|confirmed',
        ]);
        $theRequest = $request->all();
        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $theRequest['password'] = bcrypt($request->new_password);
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('messages.Password not equals')
            ]);
        }
        if ($user->update($theRequest)) {
            return response()->json(['success' => true, 'message' => trans('messages.Updated Successfully'), 'data' => $user], 200);
        }
        return response()->json([
            'success' => false,
            'message' => trans('messages.Sorry! Something went wrong.')
        ]);
    }

    public function change_user_password(Request $request)
    {
        $user = Auth::user();
        $theRequest = $request->all();
        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            if (Hash::check($request->password, $user->password)) {
                $theRequest['password'] = bcrypt($request->new_password);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.Old Password is wrong')
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('messages.Password not equals')
            ]);
        }
        if ($user->update($theRequest)) {
            return response()->json(['success' => true, 'message' => trans('messages.Updated Successfully'), 'data' => $user], 200);
        }
        return response()->json([
            'success' => false,
            'message' => trans('messages.Sorry! Something went wrong.')
        ]);
    }
}
