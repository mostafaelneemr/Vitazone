<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\PaytmController;
use App\User;
use App\UserToken;
use Auth;
use Session;
use App\Wallet;
use App\Utility\PayhereUtility;
use Redirect;


class WalletController extends Controller
{
    public function index()
    {
        $walletUser = Auth::user();
        if (isset($_GET['paysky'])) {
            $walletPaysky = $_GET['paysky'];
        } else {
            $walletPaysky = false;
        }
        if (isset($_GET['fawry'])) {
            $walletfawry = $_GET['fawry'];
        } else {
            $walletfawry = false;
        }
        $wallets = Wallet::where('user_id', Auth::user()->id)->paginate(9);
        return view('frontend.wallet', compact('wallets', 'walletPaysky', 'walletfawry', 'walletUser'));
    }

    //saved cards

    public function saved_cards()
    {
        $saved_cards = UserToken::where('user_id', Auth::user()->id)->paginate(9);
        return view('frontend.saved_cards', compact('saved_cards'));
    }

    public function delete_saved_cards($id)
    {
        $saved_card = UserToken::findOrFail($id);
        $saved_card->delete();
        return redirect()->route('saved_cards');
    }

    public function recharge(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['payment_method'] = $request->payment_option;

        $request->session()->put('payment_type', 'wallet_payment');
        $request->session()->put('payment_data', $data);
        if ($request->payment_option == 'paysky') {
            return redirect()->route('wallet.index', ['paysky' => $request->amount]);
        } elseif ($request->payment_option == 'fawry') {
            return redirect()->route('wallet.index', ['fawry' => $request->amount]);
        } elseif ($request->payment_option == 'paypal') {
            $paypal = new PaypalController;
            return $paypal->getCheckout();
        } elseif ($request->payment_option == 'stripe') {
            $stripe = new StripePaymentController;
            return $stripe->stripe();
        } elseif ($request->payment_option == 'sslcommerz') {
            $sslcommerz = new PublicSslCommerzPaymentController;
            return $sslcommerz->index($request);
        } elseif ($request->payment_option == 'instamojo') {
            $instamojo = new InstamojoController;
            return $instamojo->pay($request);
        } elseif ($request->payment_option == 'razorpay') {
            $razorpay = new RazorpayController;
            return $razorpay->payWithRazorpay($request);
        } elseif ($request->payment_option == 'paystack') {
            $paystack = new PaystackController;
            return $paystack->redirectToGateway($request);
        } elseif ($request->payment_option == 'voguepay') {
            $voguepay = new VoguePayController;
            return $voguepay->customer_showForm();
        } elseif ($request->payment_option == 'payhere') {
            $order_id = rand(100000, 999999);
            $user_id = Auth::user()->id;
            $amount = $request->amount;
            $first_name = Auth::user()->name;
            $last_name = 'X';
            $phone = '123456789';
            $email = Auth::user()->email;
            $address = 'dummy address';
            $city = 'Colombo';

            return PayhereUtility::create_wallet_form($user_id, $order_id, $amount, $first_name, $last_name, $phone, $email, $address, $city);
        } elseif ($request->payment_option == 'ngenius') {
            $ngenius = new NgeniusController();
            return $ngenius->pay();
        } else if ($request->payment_option == 'mpesa') {
            $mpesa = new MpesaController();
            return $mpesa->pay();
        } else if ($request->payment_option == 'flutterwave') {
            $flutterwave = new FlutterwaveController();
            return $flutterwave->pay();
        } elseif ($request->payment_option == 'paytm') {
            $paytm = new PaytmController;
            return $paytm->index();
        }
    }

    public function wallet_payment_paysky_done(Request $request)
    {
        $user = Auth::user();
        $user->balance = $user->balance + $request->amount;
        $user->save();
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $request->amount;
        $wallet->payment_method = 'paysky';
        $wallet->approval = 1;
        $wallet->payment_details = 'Charged with paysky';
        $wallet->save();
        flash(translate('Wallet charged successfuly'))->success();
        return response()->json(true);
    }

    public function wallet_payment_fawry_done(Request $request)
    {
        $user = Auth::user();
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = json_decode($_GET['chargeResponse'])->merchantRefNumber;
        $wallet->payment_method = 'fawry';
        $wallet->fawry_ref_num = json_decode($_GET['chargeResponse'])->fawryRefNumber;
        $wallet->approval = 0;
        $wallet->payment_details = 'Charged with fawry';
        $wallet->save();
        flash(translate('Wallet charged successfuly and waiting for your payment'))->success();
        return redirect()->route('wallet.index');
    }

    public function wallet_payment_fawry_done_app(Request $request)
    {

        $user_id_amount = explode('-', json_decode($_GET['chargeResponse'])->merchantRefNumber);
        $user = User::findOrFail($user_id_amount[0]);
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $user_id_amount[1];
        $wallet->payment_method = 'fawry';
        $wallet->fawry_ref_num = json_decode($_GET['chargeResponse'])->fawryRefNumber;
        $wallet->approval = 0;
        $wallet->payment_details = 'Charged with fawry';
        $wallet->save();
        flash(translate('Wallet charged successfuly and waiting for your payment'))->success();
        return redirect()->route('wallet_payment_fawry_done_app_redirect',['lang' => $user_id_amount[2]]);
        // return view('frontend.wallet_payment_fawry_done_app', compact('wallet'));
    }
    public function wallet_payment_fawry_done_app_redirect()
    {
        $lang = $_GET['lang'];
        return view('frontend.wallet_payment_fawry_done_app',compact('lang'));
    }
    public function wallet_payment_fawry_faile_app(Request $request)
    {
        flash(translate('Your payment not completed please try again'))->success();
        $lang = explode('-', $_GET['merchantRefNum'])[2];
        return view('frontend.wallet_payment_fawry_faile_app',compact('lang'));
    }
    public function wallet_payment_fawry_faile(Request $request)
    {
        flash(translate('Your payment not completed please try again'))->success();
        return redirect()->route('wallet.index');
    }
    public function wallet_payment_done($payment_data, $payment_details)
    {
        $user = Auth::user();
        $user->balance = $user->balance + $payment_data['amount'];
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data['amount'];
        $wallet->payment_method = $payment_data['payment_method'];
        $wallet->payment_details = $payment_details;
        $wallet->save();

        Session::forget('payment_data');
        Session::forget('payment_type');

        flash(translate('Payment completed'))->success();
        return redirect()->route('wallet.index');
    }

    public function offline_recharge(Request $request)
    {
        $wallet = new Wallet;
        $wallet->user_id = Auth::user()->id;
        $wallet->amount = $request->amount;
        $wallet->payment_method = $request->payment_option;
        $wallet->payment_details = $request->trx_id;
        $wallet->approval = 0;
        $wallet->offline_payment = 1;
        if ($request->hasFile('photo')) {
            $wallet->reciept = $request->file('photo')->store('uploads/wallet_recharge_reciept');
        }
        $wallet->save();
        flash(translate('Offline Recharge has been done. Please wait for response.'))->success();
        return redirect()->route('wallet.index');
    }

    public function offline_recharge_request()
    {
        $wallets = Wallet::where('offline_payment', 1)->paginate(10);
        return view('manual_payment_methods.wallet_request', compact('wallets'));
    }

    public function updateApproved(Request $request)
    {
        $wallet = Wallet::findOrFail($request->id);
        $wallet->approval = $request->status;
        if ($request->status == 1) {
            $user = $wallet->user;
            $user->balance = $user->balance + $wallet->amount;
            $user->save();
        } else {
            $user = $wallet->user;
            $user->balance = $user->balance - $wallet->amount;
            $user->save();
        }
        if ($wallet->save()) {
            return 1;
        }
        return 0;
    }
}
