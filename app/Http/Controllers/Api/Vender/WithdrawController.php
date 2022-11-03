<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use App\Http\Resources\SellerWithdrawRequestCollection;
use Illuminate\Http\Request;

use App\SellerWithdrawRequest;

class WithdrawController extends Controller
{

    public function index()
    {
        $seller_withdraw_request = SellerWithdrawRequest::where('user_id', auth('api')->user()->id)->get();
        return response()->json([
            'success' => true,
            'balance' => auth('api')->user()->seller->admin_to_pay,

            'seller_withdraw_request' => $seller_withdraw_request,
        ], 200);

        // return new SellerWithdrawRequestCollection(SellerWithdrawRequest::where('user_id', auth('api')->user()->id)->get());
    }

    public function withdraw(Request $request)
    {
        $seller_withdraw_request = new SellerWithdrawRequest;
        $seller_withdraw_request->user_id = auth('api')->user()->id;
        $seller_withdraw_request->amount = $request->amount;
        $seller_withdraw_request->message = $request->message;
        $seller_withdraw_request->status = '0';
        $seller_withdraw_request->viewed = '0';
        $seller_withdraw_request->save();

        return response()->json([
            'success' => true,
            'seller_withdraw_request' => $seller_withdraw_request
        ]);
    }
}
