<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserCollection;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function info($id)
    {
        return new UserCollection( User::where( 'id', $id )->get() );
    }

    public function updateName(Request $request)
    {
        $user = User::findOrFail( $request->user_id );
        $user->update( [
            'name' => $request->name
        ] );
        return response()->json( [
            'message' => trans('messages.Profile information has been updated successfully')
        ] );
    }

    public function updateShippingAddress(Request $request)
    {
        $user = User::findOrFail( $request->user_id );
        $user->update( [
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'phone' => $request->phone,
        ] );
        return response()->json( [
            'message' => trans('messages.Shipping information has been updated successfully')
        ] );
    }

    public function policies()
    {
        return response()->json( ['terms'=>route('terms'),
            'privacypolicy'=>route('privacypolicy'),
            'supportpolicy'=>route('supportpolicy'),
            'returnpolicy'=>route('returnpolicy'),
            'sellerpolicy'=>route('sellerpolicy')
        ] ,200);
    }
}
