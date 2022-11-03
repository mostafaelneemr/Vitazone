<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

	public function index()
	{
		$user = \App\User::find(auth('api')->user()->id);
		$bank_info = $user->seller;

		return response()->json([
            'user' => $user,
            'bank_info' => $bank_info
        ]);
	}


    public function update(Request $request)
    {
    	$user = \App\User::find(auth('api')->user()->id);
        if($user!=null){
            $user->name = $request->name;
            $user->address = $request->address;
            $user->country = $request->country;
            $user->city = $request->city;
            $user->postal_code = $request->postal_code;
            $user->phone = $request->phone;

            if($request->new_password != null && ($request->new_password == $request->confirm_password)){
                $user->password = Hash::make($request->new_password);
            }

            if($request->hasFile('photo')){
                $user->avatar_original = $request->photo->store('uploads');
            }

            // print_r($user->seller);
            // exit();

            $seller = $user->seller;
            $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
            $seller->bank_payment_status = $request->bank_payment_status;
            $seller->bank_name = $request->bank_name;
            $seller->bank_acc_name = $request->bank_acc_name;
            $seller->bank_acc_no = $request->bank_acc_no;
            $seller->bank_routing_no = $request->bank_routing_no;
            $seller->save();

            $user->save();

            return response()->json([
                'user' => $user
            ]);
        }

        return response()->json([
                'error' => "No user found"
            ]);
    }
}
