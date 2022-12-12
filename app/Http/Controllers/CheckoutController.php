<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\PaytmController;
use App\Order;
use App\BusinessSetting;
use App\Coupon;
use App\CouponUsage;
use App\User;
use App\Address;
use App\Models\Product;
use App\OrderDetail;
use App\UserToken;
use App\Utility\CategoryUtility;
use Session;
use App\Utility\PayhereUtility;
use App\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {

        if ($request->payment_option != null) {

            $orderController = new OrderController;

            $orderController->store($request);

            $request->session()->put('payment_type', 'cart_payment');
            if ($request->session()->get('order_id') != null) {
                if ($request->payment_option == 'paypal') {
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
                    $voguePay = new VoguePayController;
                    return $voguePay->customer_showForm();
                } elseif ($request->payment_option == 'twocheckout') {
                    $twocheckout = new TwoCheckoutController;
                    return $twocheckout->index($request);
                } elseif ($request->payment_option == 'payhere') {
                    $order = Order::findOrFail($request->session()->get('order_id'));

                    $order_id = $order->id;
                    $amount = $order->grand_total;
                    $first_name = json_decode($order->shipping_address)->name;
                    $last_name = 'X';
                    $phone = json_decode($order->shipping_address)->phone;
                    $email = json_decode($order->shipping_address)->email;
                    $address = json_decode($order->shipping_address)->address;
                    $city = json_decode($order->shipping_address)->city;

                    return PayhereUtility::create_checkout_form($order_id, $amount, $first_name, $last_name, $phone, $email, $address, $city);
                } else if ($request->payment_option == 'ngenius') {
                    $ngenius = new NgeniusController();
                    return $ngenius->pay();
                } else if ($request->payment_option == 'flutterwave') {
                    $flutterwave = new FlutterwaveController();
                    return $flutterwave->pay();
                } else if ($request->payment_option == 'mpesa') {
                    $mpesa = new MpesaController();
                    return $mpesa->pay();
                } elseif ($request->payment_option == 'paytm') {
                    $paytm = new PaytmController;
                    return $paytm->index();
                } elseif ($request->payment_option == 'cash_on_delivery') {
                    $request->session()->put('cart', collect([]));

                    // $request->session()->forget('order_id');
                    $request->session()->forget('delivery_info');
                    $request->session()->forget('coupon_id');
                    $request->session()->forget('coupon_discount');
                    $request->session()->forget('wallet_discount');

                    flash(translate("Your order has been placed successfully"))->success();
                    return redirect()->route('order_confirmed');
                } elseif ($request->payment_option == 'wallet') {
                    $user = Auth::user();
                    $user->balance -= Order::findOrFail($request->session()->get('order_id'))->grand_total;
                    $user->save();
                    return $this->checkout_done($request->session()->get('order_id'), null);
                } elseif ($request->payment_option == 'fawry') {
                    return redirect('checkout/payment_select?fawry=true');
                } elseif ($request->payment_option == 'paysky') {
                    return redirect('checkout/payment_select?paysky=true');
                } elseif ($request->payment_option == 'paymob_visa_master_card') {
                    if(Auth::user()->email || session::get('shipping_info')['email'] != null){ 
                        $invalidData = false;
                        $iframe = payWithPaymob('online_card');
                        return view('frontend.paymob_visa', compact('iframe', 'invalidData'));
                    } else { 
                        flash(translate('please insert your email address'));
                        return redirect()->back();
                    }
                } elseif ($request->payment_option == 'paymob_bank_instalments') {
                    if(Auth::user()->email || session::get('shipping_info')['email'] != null){ 
                        $invalidData = false;
                        $iframe = payWithPaymob('bank_installment');
                        return view('frontend.paymob_visa', compact('iframe', 'invalidData'));
                        }else {
                            flash(translate('please insert your email address'));
                            return redirect()->back();
                        }
                } elseif ($request->payment_option == 'paymob_valu') {
                    $invalidData = false;
                    $iframe = payWithPaymob('paymob_valu');
                    return view('frontend.paymob_valu', compact('iframe', 'invalidData'));
                } elseif ($request->payment_option == 'paymob_wallet') {
                    $invalidData = false;
                    $invalidNumber = false;
                    return view('frontend.paymob_wallet', compact('invalidData'));
                } else {
                    $order = Order::findOrFail($request->session()->get('order_id'));
                    $order->manual_payment = 1;
                    $order->save();

                    $request->session()->put('cart', collect([]));
                    // $request->session()->forget('order_id');
                    $request->session()->forget('delivery_info');
                    $request->session()->forget('coupon_id');
                    $request->session()->forget('coupon_discount');

                    flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                    return redirect()->route('order_confirmed');
                }
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
    }
    public function paymob_mobilenumber(Request $request)
    {
        $response_token = payWithPaymob('paymob_wallet');
        $response_wallet = Http::withHeaders(
            ['content-type' => 'application/json']
        )->post(
            'https://accept.paymob.com/api/acceptance/payments/pay',
            [
                "source" => [
                    "identifier" =>  $request->wallet_number,
                    "subtype" => "WALLET"
                ],
                "payment_token" => $response_token
            ]
        );
        $final_response_wallet = $response_wallet->json();
        if ($final_response_wallet['pending'] == true) {
            return redirect($final_response_wallet['redirect_url']);
        } else {
            $invalidData = true;
            $invalidNumber = true;
            return view('frontend.paymob_wallet', compact('invalidData', 'invalidNumber'));
        }
    }
    public function paymob_wallet_sucsess(Request $request)
    {
        $order = Order::where('payment_refrence', $request->order)->first();
        if ($request->success == "true") {
            return $this->checkout_done($order->id, $request->source_data_sub_type, $request->id, $request->order);
        } else {
            $invalidData = true;
            $invalidNumber = false;
            return view('frontend.paymob_wallet', compact('invalidNumber', 'invalidData'));
        }
    }
    public function paywith_savedcards($id)
    {
        $user_token = UserToken::where('id', $id)->first();
        $payment_token = payWithPaymob('saved_token');
        $response_wallet = Http::withHeaders(
            ['content-type' => 'application/json']
        )->post(
            'https://accept.paymobsolutions.com/api/acceptance/payments/pay',
            [
                "source" => [
                    "identifier" =>  $user_token->token,
                    "subtype" => "TOKEN"
                ],
                "payment_token" => $payment_token
            ]
        );
        $order = Order::where('payment_refrence', $response_wallet['order'])->first();
        if ($response_wallet['success'] == "true") {
            return $this->checkout_done($order->id, $response_wallet['source_data.sub_type'], $response_wallet['id'], $response_wallet['order']);
        } else {
            $invalidData = true;
            $iframe = payWithPaymob('paymob_visa_master_card');
            return view('frontend.paymob_visa', compact('iframe', 'invalidData'));
        }
    }
    public function paymob_sucsess(Request $request)
    {
        $order = Order::where('payment_refrence', $request->order)->first();
        if ($request->success == "true") {
            return $this->checkout_done($order->id, $request->source_data_sub_type, $request->id, $request->order);
        } else {
            $invalidData = true;
            $iframe = payWithPaymob('paymob_visa_master_card');
            return view('frontend.paymob_visa', compact('iframe', 'invalidData'));
        }
    }

    public function paymob_valu_sucsess(Request $request)
    {
        $order = Order::where('payment_refrence', $request->order)->first();
        if ($request->success == "true") {
            return $this->checkout_done($order->id, $request->source_data_sub_type, $request->id, $request->order);
        } else {
            $invalidData = true;
            $iframe = payWithPaymob('paymob_valu');
            return view('frontend.paymob_valu', compact('iframe', 'invalidData'));
        }
    }


    public function paymob_procecced_callback(Request $request)
    {
        if ($request['type'] == 'TOKEN') {
            $user_token = new UserToken;
            $user_token->user_id = auth()->user()->id;
            $user_token->returned_id = $request["obj"]["id"];
            $user_token->token = $request["obj"]["token"];
            $user_token->masked_pan = $request["obj"]["masked_pan"];
            $user_token->card_subtype = $request["obj"]["card_subtype"];
            $user_token->save();
        }
        $user_tokenw = new UserToken;
        $user_tokenw->ssssssss = $request;
        $user_tokenw->save();
        return response()->json(true);
    }
    //redirects to this method after a successfull checkout
    public function checkout_done($order_id, $payment, $payment_merchent_ref = 0, $payment_refrence = 0)
    {
        $order = Order::findOrFail($order_id);
        $order->payment_status = 'paid';
        $order->payment_details = $payment;
        $cleint = User::find($order->user_id);
        if ($payment == 'fawry') {
            $order->payment_merchent_ref = $payment_merchent_ref;
            $order->payment_refrence = $payment_refrence;
            $order->payment_status = 'unpaid';

            if ($cleint->device_token != null) {
                if ($cleint->lang = 'ar') {
                    $notification_title = 'ماي ميديكال دفع بوسطة فوري';
                    $notification_text = 'سوف يتم دفع سعر الطلب عن طريق فوري.';
                } else {
                    $notification_title = 'My Medical Fawry Payment';
                    $notification_text = 'You will pay to order with fawry payment method .';
                }
                $notification_body['reciever_id'] = $cleint->id;
                $notification_body['type'] = 'order';
                $notification_body['order_id'] = $order->id;
                $notification_body['title'] = $notification_title;
                $notification_body['text'] = $notification_text;
                $notification_body['click_action'] = 'MedicalApp';
                $notification_body['sound'] = true;
                $notification_body['icon'] = 'logo';
                $notification_body['android_channel_id'] = 'android_channel_id';
                $notification_body['high_priority'] = 'high_priority';
                $notification_body['show_in_foreground'] = true;
                sendNotification($notification_body, $cleint->device_token);
            }
        }
        if ($payment == 'paysky') {
            $order->payment_merchent_ref = $payment_merchent_ref;
            $order->payment_refrence = $payment_refrence;
            if ($cleint->device_token != null) {
                if ($cleint->lang = 'ar') {
                    $notification_title = 'فيتازون دفع بوسطة باي سكاي';
                    $notification_text = 'سوف يتم دفع سعر الطلب عن طريق باي سكاي.';
                } else {
                    $notification_title = 'Vitazone Paysky Payment';
                    $notification_text = 'You will pay to order with Paysky payment method .';
                }
                $notification_body['reciever_id'] = $cleint->id;
                $notification_body['type'] = 'order';
                $notification_body['order_id'] = $order->id;
                $notification_body['title'] = $notification_title;
                $notification_body['text'] = $notification_text;
                $notification_body['click_action'] = 'MedicalApp';
                $notification_body['sound'] = true;
                $notification_body['icon'] = 'logo';
                $notification_body['android_channel_id'] = 'android_channel_id';
                $notification_body['high_priority'] = 'high_priority';
                $notification_body['show_in_foreground'] = true;
                sendNotification($notification_body, $cleint->device_token);
            }
        }
        if ($payment == 'MasterCard' || $payment == 'Visa' || $payment ==  'valu') {
            $order->payment_merchent_ref = $payment_merchent_ref;
            $order->payment_refrence = $payment_refrence;
            $order->payment_type = $payment;
            if ($cleint->device_token != null) {
                if ($cleint->lang = 'ar') {
                    $notification_title = 'فيتازون دفع بوسطة باي موب';
                    $notification_text = 'سوف يتم دفع سعر الطلب عن طريق باي موب.';
                } else {
                    $notification_title = 'Vitazone Paymob Payment';
                    $notification_text = 'You will pay to order with Paymob payment method .';
                }
                $notification_body['reciever_id'] = $cleint->id;
                $notification_body['type'] = 'order';
                $notification_body['order_id'] = $order->id;
                $notification_body['title'] = $notification_title;
                $notification_body['text'] = $notification_text;
                $notification_body['click_action'] = 'MedicalApp';
                $notification_body['sound'] = true;
                $notification_body['icon'] = 'logo';
                $notification_body['android_channel_id'] = 'android_channel_id';
                $notification_body['high_priority'] = 'high_priority';
                $notification_body['show_in_foreground'] = true;
                sendNotification($notification_body, $cleint->device_token);
            }
        }
        $order->save();

        if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
            $affiliateController = new AffiliateController;
            $affiliateController->processAffiliatePoints($order);
        }

        if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {
            $clubpointController = new ClubPointController;
            $clubpointController->processClubPoints($order);
        }
        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() == null || !\App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                foreach ($order->orderDetails as $key => $orderDetail) {
                    if ($order->payment_type != 'fawry') {
                        $orderDetail->payment_status = 'paid';
                    }
                    $orderDetail->save();
                    if ($orderDetail->product->user->user_type == 'seller') {
                        $seller = $orderDetail->product->user->seller;
                        $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price * (100 - $commission_percentage)) / 100 + $orderDetail->tax + $orderDetail->shipping_cost;
                        $seller->save();
                    }
                }
            } else {
                foreach ($order->orderDetails as $key => $orderDetail) {
                    if ($order->payment_type != 'fawry') {
                        $orderDetail->payment_status = 'paid';
                    }
                    $orderDetail->save();
                    if ($orderDetail->product->user->user_type == 'seller') {

                        $commission_percentage = $orderDetail->product->category->commision_rate;
                        $seller = $orderDetail->product->user->seller;
                        $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price * (100 - $commission_percentage)) / 100 + $orderDetail->tax + $orderDetail->shipping_cost;
                        $seller->save();
                    }
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                if ($order->payment_type != 'fawry') {
                    $orderDetail->payment_status = 'paid';
                }
                $orderDetail->save();
                if ($orderDetail->product->user->user_type == 'seller') {
                    $seller = $orderDetail->product->user->seller;
                    $seller->admin_to_pay = $seller->admin_to_pay + $orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost;
                    $seller->save();
                }
            }
        }

        $order->commission_calculated = 1;
        $order->save();

        Session::put('cart', collect([]));
        // Session::forget('order_id');
        Session::forget('payment_type');
        Session::forget('delivery_info');
        Session::forget('coupon_id');
        Session::forget('coupon_discount');
        Session::forget('shipping_info');


        flash(translate('Payment completed'))->success();
        return view('frontend.order_confirmed', compact('order'));
    }

    public function fawry_sucsess()
    {
        $order_id = explode('_', json_decode($_GET['chargeResponse'])->merchantRefNumber)[0];
        $order = Order::findOrFail($order_id);
        $order->payment_details = 'fawry';
        $order->payment_type = 'fawry';
        $order->payment_merchent_ref = json_decode($_GET['chargeResponse'])->merchantRefNumber;
        $order->payment_refrence = json_decode($_GET['chargeResponse'])->paymentMethod;
        $order->payment_status = 'unpaid';
        $order->save();
        return view('frontend.fawry_sucsess', compact('order'));
    }



    public function paysky_sucsess(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_type = 'paysky';
        $order->payment_merchent_ref = $request->MerchantReference;
        $order->payment_refrence = $request->SystemReference;
        $order->payment_status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->payment_status = 'paid';
            $orderDetail->save();
        }
        $order->save();

        return response()->json(true);
    }
    public function pay_with_fawry($lang, $order_id, $total_money, $costomer_name, $customer_phone, $customer_email, $customer_id)
    {
        $order['lang'] = $lang;
        $order['order_id'] = $order_id;
        $order['total_money'] = $total_money;
        $order['costomer_name'] = $costomer_name;
        $order['customer_phone'] = $customer_phone;
        $order['customer_email'] = $customer_email;
        $order['customer_id'] = $customer_id;
        return view('frontend.pay_with_fawry', compact('order'));
    }

    public function charge_wallet_with_fawry($lang, $customer_id, $amount)
    {
        $user = User::findOrFail($customer_id);
        $wallet['lang'] = $lang;
        $wallet['user_id'] = $user->id;
        $wallet['amount'] = $amount;
        $wallet['email'] = $user->email;
        $wallet['phone'] = $user->phone;
        $wallet['name'] = $user->name;
        return view('frontend.charge_wallet_with_fawry', compact('wallet'));
    }

    public function pay_with_paysky($order_id, $total_money)
    {
        $order['order_id'] = $order_id;
        $order['total_money'] = $total_money;
        return view('frontend.pay_with_paysky', compact('order'));
    }

    public function wallet_paysky_sucsess(Request $request)
    {
        $user = User::find($request->user_id);
        $user->balance = $user->balance + $request->amount;
        $user->save();
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $request->amount;
        $wallet->payment_method = 'paysky';
        $wallet->approval = 1;
        $wallet->payment_details = 'Charged with paysky';
        $wallet->save();
        return response()->json(true);
    }
    public function recharge_wallet_with_paysky($user_id, $total_money)
    {
        $wallet['user_id'] = $user_id;
        $wallet['total_money'] = $total_money;
        return view('frontend.recharge_wallet_with_paysky', compact('wallet'));
    }

    public function get_shipping_info(Request $request)
    {
        if (Session::has('cart') && count(Session::get('cart')) > 0) {
            $categories = Category::where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get();
            $addresses = Address::where('user_id', Auth::user()->id)->get();
            if (sizeof($addresses) > 0) {
                $default_phone = false;
                foreach ($addresses as $key => $address) {
                    if ($address->set_default == true) {
                        $default_phone = $address->phone;
                    }
                }
                if ($default_phone == false) {
                    $default_phone = $addresses[0]->phone;
                }
            } else {
                $default_phone = false;
            }
            return view('frontend.view_cart', compact('categories', 'default_phone'));

            if (Auth::check()) {
                if ($request->address_id == null) {
                    flash(translate("Please add shipping address"))->warning();
                    return back();
                }

                $address = Address::findOrFail($request->address_id);
                $data['name'] = Auth::user()->name;
                $data['email'] = Auth::user()->email;
                $data['address'] = $address->address;
                $data['country'] = $address->country;
                $data['city'] = $address->city;
                $data['phone'] = $address->phone;
                $data['checkout_type'] = $request->checkout_type;
                $data['shipping_cost'] = $address->addressRegion->shipping_cost;
            } else {
                $data['name'] = $request->name;
                $data['email'] = $request->email;
                $data['address'] = $request->address;
                $data['country'] = $request->country;
                $data['city'] = $request->city;
                $data['postal_code'] = $request->postal_code;
                $data['phone'] = $request->phone;
                $data['checkout_type'] = $request->checkout_type;
                $data['shipping_cost'] = 0;
            }

            $shipping_info = $data;
            $request->session()->put('shipping_info', $shipping_info);

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            foreach (Session::get('cart') as $key => $cartItem) {
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $shipping += $cartItem['shipping'];
            }

            $total = $subtotal + $tax + $shipping;

            if (Session::has('coupon_discount')) {
                $total -= Session::get('coupon_discount');
            }


            return view('frontend.delivery_info');




            $request->session()->put('cart', $cart);
            return view('frontend.shipping_info', compact('categories'));
        }
        flash(translate('Your cart is empty'))->success();
        return back();
    }

    public function store_shipping_info(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3',
            'email' => 'required|email|ends_with:gmail.com,hotmail.com,yahoo.com',
            'address' => 'required|string',
            'phone' => ["required","digits:11","regex:/01[0125][0-9]{8}$/","numeric"],
        ]);
        try {


            if (count($request->all()) > 0) {
                if (Auth::check()) {
                    $products_types = array_column(\App\Product::whereIn('id', array_column(Session::get('cart')->toArray(), 'id'))->select('id', 'light_heavy_shipping')->get()->toArray(), 'light_heavy_shipping');
                    if ($request->address_id == null) {
                        flash(translate("Please add shipping address"))->warning();
                        return back();
                    }
    
                    $address = Address::findOrFail($request->address_id);
    
                    if (in_array('heavy', $products_types)) {
                        $shipping_cost = $address->addressRegion->shipping_cost_high;
                        $shipping_date = date('d-m-Y', strtotime('+' . $address->addressRegion->shipping_duration_high . ' days'));
                    } else {
                        $shipping_cost = $address->addressRegion->shipping_cost;
                        $shipping_date = date('d-m-Y', strtotime('+' . $address->addressRegion->shipping_duration . ' days'));
                    }
    
                    $data['name'] = Auth::user()->name;
                    $data['email'] = Auth::user()->email;
                    $data['address'] = $address->address;
                    $data['country'] = $address->country;
                    $data['city'] = $address->city;
                    $data['region'] = $address->region;
                    $data['postal_code'] = $address->postal_code;
                    $data['phone'] = $address->phone;
                    $data['checkout_type'] = $request->checkout_type;
                    // $data['shipping_cost'] = $shipping_cost;
                    $data['shipping_date'] = $shipping_date;
                } else {
                    $data['name'] = $request->name;
                    $data['email'] = $request->email;
                    $data['address'] = $request->address;
                    $data['country'] = $request->country;
                    $data['city'] = $request->city;
                    $data['region'] = $request->region;
                    $data['postal_code'] = $request->postal_code;
                    $data['phone'] = $request->phone;
                    $data['checkout_type'] = $request->checkout_type;
                    // $data['shipping_cost'] = 0;
                    $data['shipping_date'] = date('d-m-Y');
                }
    
    
                $shipping_info = $data;
                $request->session()->put('shipping_info', $shipping_info);
    
                $subtotal = 0;
                $tax = 0;
                $shipping = 0;
                foreach (Session::get('cart') as $key => $cartItem) {
                    $subtotal += $cartItem['price'] * $cartItem['quantity'];
                    $tax += $cartItem['tax'] * $cartItem['quantity'];
                    $shipping += $cartItem['shipping'];
                }
                // return date('d-m-Y',strtotime('+2 days'));
                $cart = $request->session()->get('cart', collect([]));
                $cart = $cart->map(function ($object, $key) use ($request, $data) {
                    $object['shipping'] = getShippingCost($key);
                    $object['shipping_date'] = $data['shipping_date'];
                    return $object;
                });
    
                $request->session()->put('cart', $cart);
                $total = $subtotal + $tax + $shipping;
    
                if (Session::has('coupon_discount')) {
                    $total -= Session::get('coupon_discount');
                }
            }
    
    
            return view('frontend.delivery_info');
        } catch (\Exception $e) {
            // flash();
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    public function store_delivery_info(Request $request)
    {
        if (Session::has('cart') && count(Session::get('cart')) > 0) {
            $cart = $request->session()->get('cart', collect([]));
            // return $request;
            $cart = $cart->map(function ($object, $key) use ($request) {
                if (\App\Product::find($object['id'])->added_by == 'admin') {
                    if ($request['shipping_type_admin'] == 'home_delivery') {
                        $object['shipping_type'] = 'home_delivery';
                    } else {
                        $object['shipping_type'] = 'pickup_point';
                        $object['pickup_point'] = $request->pickup_point_id_admin;
                    }
                } else {
                    if ($request['shipping_type_' . \App\Product::find($object['id'])->user_id] == 'home_delivery') {
                        $object['shipping_type'] = 'home_delivery';
                    } else {
                        $object['shipping_type'] = 'pickup_point';
                        $object['pickup_point'] = $request['pickup_point_id_' . \App\Product::find($object['id'])->user_id];
                    }
                }
                return $object;
            });

            $request->session()->put('cart', $cart);

            $cart = $cart->map(function ($object, $key) use ($request, $cart) {
                //$object['shipping'] = session()->all()['shipping_info']['shipping_cost'];
                // $object['shipping'] = $cart[$key]['shipping'];
                $object['shipping'] = getShippingCost($key);
                return $object;
            });

            $request->session()->put('cart', $cart);

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            foreach (Session::get('cart') as $key => $cartItem) {
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $shipping += $cartItem['shipping'];
            }
            $total = $subtotal + $tax + $shipping;

            if (Session::has('coupon_discount')) {
                $total -= Session::get('coupon_discount');
            }

            //dd($total);
            $fawry = false;
            $paysky = false;
            $shipping_info['name'] = '';
            $shipping_info['phone'] = '';
            $shipping_info['email'] = '';
            if (Session::get('wallet_discount') != null) {
                $total -= Session::get('wallet_discount');
            }
            return view('frontend.payment_select', compact('total', 'fawry', 'paysky', 'shipping_info'));
        } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }
    }

    public function applay_wallet_discount(Request $request)
    {
        Session::put('wallet_discount', $request->discount_val);
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach (Session::get('cart') as $key => $cartItem) {
            $subtotal += $cartItem['price'] * $cartItem['quantity'];
            $tax += $cartItem['tax'] * $cartItem['quantity'];
            $shipping += $cartItem['shipping'];
        }
        $total = $subtotal + $tax + $shipping;
        $total -= Session::get('wallet_discount');
        return response()->json(true);
    }

    public function getCartItems(Request $request)
    {
        $products_ids = array_column(Session::get('cart')->toArray(), 'id');
        $productArr = Product::select('id', 'name_' . locale() . ' AS name', 'thumbnail_img', 'unit_price')->whereIn('id', $products_ids)->get();
        foreach ($productArr as $key => $product) {
            foreach (Session::get('cart') as $key2 => $cartItem) {
                if ($cartItem['id'] == $productArr[$key]->id) {
                    $productArr[$key]['quantity'] = $cartItem['quantity'];
                }
                $productArr[$key]['thumbnail_img'] = url($productArr[$key]->thumbnail_img);
            }
        }
        return response()->json($productArr);
    }

    public function getOrderItems(Request $request)
    {
        $orderDetails = OrderDetail::where('order_id', $request->order_id)->select('id', 'product_id', 'price', 'shipping_cost', 'quantity')->get()->toArray();
        $products_ids = array_column($orderDetails, 'product_id');
        $productArr = Product::select('id', 'name_' . locale() . ' AS name', 'thumbnail_img', 'unit_price')->whereIn('id', $products_ids)->get();
        foreach ($productArr as $key => $product) {
            foreach ($orderDetails as $key2 => $orderDetail) {
                if ($orderDetail['product_id'] == $productArr[$key]->id) {
                    $productArr[$key]['quantity'] = $orderDetail['quantity'];
                }
                $productArr[$key]['thumbnail_img'] = url($productArr[$key]->thumbnail_img);
            }
        }
        return response()->json($productArr);
    }
    public function get_payment_info(Request $request)
    {
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach (Session::get('cart') as $key => $cartItem) {
            $subtotal += $cartItem['price'] * $cartItem['quantity'];
            $tax += $cartItem['tax'] * $cartItem['quantity'];
            $shipping += $cartItem['shipping'];
        }

        $total = $subtotal + $tax + $shipping;

        if (Session::has('coupon_discount')) {
            $total -= Session::get('coupon_discount');
        }
        if (Session::get('wallet_discount') != null) {
            $total -= Session::get('wallet_discount');
        }
        if ($request->fawry) {
            $fawry = true;
        } else {
            $fawry = false;
        }
        if ($request->paysky) {
            $paysky = true;
        } else {
            $paysky = false;
        }
        $shipping_info = Session::get('shipping_info');
        // return $shipping_info;
        return view('frontend.payment_select', compact('total', 'fawry', 'paysky', 'shipping_info'));
    }

    public function apply_coupon_code(Request $request)
    {

        $coupon = Coupon::where('code', $request->code)->first();

        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);
                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach (Session::get('cart') as $key => $cartItem) {
                            $subtotal += $cartItem['price'] * $cartItem['quantity'];
                            $tax += $cartItem['tax'] * $cartItem['quantity'];
                            $shipping += $cartItem['shipping'];
                        }
                        $sum = $subtotal + $tax + $shipping;

                        if ($sum > $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                            $request->session()->put('coupon_id', $coupon->id);
                            $request->session()->put('coupon_discount', $coupon_discount);
                            flash(translate('Coupon has been applied'))->success();
                        }
                    } elseif ($coupon->type == 'product_base') {
                        $coupon_discount = 0;
                        foreach (Session::get('cart') as $key => $cartItem) {
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += $cartItem['price'] * $coupon->discount / 100;
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount;
                                    }
                                }
                            }
                        }
                        $request->session()->put('coupon_id', $coupon->id);
                        $request->session()->put('coupon_discount', $coupon_discount);
                        flash(translate('Coupon has been applied'))->success();
                    } elseif ($coupon->type == 'category_base') {
                        $coupon_discount = 0;
                        foreach (Session::get('cart') as $key => $cartItem) {
                            $product = \App\Product::find($cartItem['id']);
                            $category_level = Category::find($coupon_details->category_id);
                            $catArr[] = $category_level->id;
                            if ($category_level->level == 0) {
                                $firstLevel_cat = CategoryUtility::get_immediate_children_ids($category_level->id);
                                foreach ($firstLevel_cat as $key => $firstLevel_cat_id) {
                                    $catArr[] = $firstLevel_cat_id;
                                    $secLevel_cat = CategoryUtility::get_immediate_children_ids($firstLevel_cat_id);
                                    foreach ($secLevel_cat as $key => $secLevel_cat_id) {
                                        $catArr[] = $secLevel_cat_id;
                                    }
                                }
                            } elseif ($category_level->level == 1) {
                                $firstLevel_cat = CategoryUtility::get_immediate_children_ids($category_level->id);
                                foreach ($firstLevel_cat as $key => $firstLevel_cat_id) {
                                    $catArr[] = $firstLevel_cat_id;
                                }
                            }
                            $product_catArr = array_column($product->subsubcategoryMany->toArray(), 'id');
                            $array_intersect = array_intersect($catArr, $product_catArr);
                            if (count($array_intersect) > 0) {
                                if ($coupon->discount_type == 'percent') {
                                    $coupon_discount += $cartItem['price'] * $coupon->discount / 100;
                                } elseif ($coupon->discount_type == 'amount') {
                                    $coupon_discount += $coupon->discount;
                                }
                            }
                        }
                        if ($coupon_discount > 0) {
                            $request->session()->put('coupon_id', $coupon->id);
                            $request->session()->put('coupon_discount', $coupon_discount);
                            flash(translate('Coupon has been applied'))->success();
                        } else {
                            flash(translate('Not applicable to this category'))->success();
                        }
                    } elseif ($coupon->type == 'user_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach (Session::get('cart') as $key => $cartItem) {
                            $subtotal += $cartItem['price'] * $cartItem['quantity'];
                            $tax += $cartItem['tax'] * $cartItem['quantity'];
                            $shipping += $cartItem['shipping'];
                        }
                        $sum = $subtotal + $tax + $shipping;
                        $user_id = auth()->user()->id;
                        if ($coupon_details->user_id == $user_id) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                            $request->session()->put('coupon_id', $coupon->id);
                            $request->session()->put('coupon_discount', $coupon_discount);
                            flash(translate('Coupon has been applied'))->success();
                        }
                    } elseif ($coupon->type == 'vendor_base') {
                        $coupon_discount = 0;
                        $seller = \App\Seller::find($coupon_details->seller_id);
                        foreach (Session::get('cart') as $key => $cartItem) {
                            $product = \App\Product::find($cartItem['id']);

                            if ($product->user_id == $seller->user_id) {
                                if ($coupon->discount_type == 'percent') {
                                    $coupon_discount += $cartItem['price'] * $coupon->discount / 100;
                                } elseif ($coupon->discount_type == 'amount') {
                                    $coupon_discount += $coupon->discount;
                                }
                            }
                        }
                        if ($coupon_discount > 0) {
                            $request->session()->put('coupon_id', $coupon->id);
                            $request->session()->put('coupon_discount', $coupon_discount);
                            flash(translate('Coupon has been applied'))->success();
                        } else {
                            flash(translate('Not applicable to this vendor'))->success();
                        }
                    }
                } else {
                    flash(translate('You already used this coupon!'))->warning();
                }
            } else {
                flash(translate('Coupon expired!'))->warning();
            }
        } else {
            // return 'pppp';
            flash(translate('Invalid coupon!'))->warning();
        }
        return back();
    }

    public function remove_coupon_code(Request $request)
    {
        $request->session()->forget('coupon_id');
        $request->session()->forget('coupon_discount');
        return back();
    }

    public function order_confirmed()
    {
        $order = Order::findOrFail(Session::get('order_id'));
        // return $order;
        return view('frontend.order_confirmed', compact('order'));
    }

    public function deeplink()
    {
        // return $_SERVER['HTTP_USER_AGENT'];
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== FALSE) {
            // redirect
            // return "dsfsdf";
            header("location: " . BusinessSetting::where('type', 'mobile_app_googleplay_link')->first()->value);
            exit();
        }
        return view('frontend.deeplink');
    }
}
