<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class FawryController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('payment_type')) {
            if (Session::get('payment_type') == 'fawry') {
                return view('frontend.fawry_payment');
            }
        }
    }

    public function payment_redirect()
    {
        $merchantRefNumber = json_decode($_GET['chargeResponse'])->merchantRefNumber;
        $fawryRefNumber = json_decode($_GET['chargeResponse'])->fawryRefNumber;
        // if(Session::get('payment_type') == 'fawry'){
        $checkoutController = new CheckoutController;
        return $checkoutController->checkout_done(Session::get('order_id'), 'fawry', $merchantRefNumber, $fawryRefNumber);

        // }else{
        //     return redirect()->route('home');
        // }
    }

    public function paysky_redirect()
    {
        $MerchantReferenece = $_GET['MerchantReference'];
        $SystemReference = $_GET['SystemReference'];
        $checkoutController = new CheckoutController;
        return $checkoutController->checkout_done(Session::get('order_id'), 'paysky', $MerchantReferenece, $SystemReference);
    }
}
