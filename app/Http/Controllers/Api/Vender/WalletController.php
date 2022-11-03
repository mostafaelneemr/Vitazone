<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\PaytmController;
use Auth;
use Session;
use App\Wallet;
use App\User;
use App\Utility\PayhereUtility;

class WalletController extends Controller
{
	public function wallet_history()
	{
		$wallets = Wallet::where('user_id', auth('api')->user()->id)->get();
		return response()->json([
            'wallets' => $wallets
        ]);
	}


    public function recharge(Request $request)
    {

        $user = User::find(auth('api')->user()->id);
        $user->balance = $user->balance + $request->amount;
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $request->amount;
        $wallet->payment_method = $request->payment_option;
        $wallet->payment_details = $request->payment_details;
        $wallet->save();

        return response()->json([
            'wallet' => $wallet
        ]);
    }
}
