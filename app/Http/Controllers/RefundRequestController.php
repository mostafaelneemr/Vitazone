<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BusinessSetting;
use App\Mail\EmailManager;
use App\RefundRequest;
use App\OrderDetail;
use App\Seller;
use App\Wallet;
use App\User;
use Auth;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Order;
use App\RefundResone;

class RefundRequestController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Store Customer Refund Request
    public function request_store(Request $request, $id)
    {
        $order_detail = OrderDetail::where('id', $id)->first();
        $refund = new RefundRequest;
        $refund->user_id = Auth::user()->id;
        $refund->order_id = $order_detail->order_id;
        $refund->order_detail_id = $order_detail->id;
        $refund->seller_id = $order_detail->seller_id;
        $refund->seller_approval = 0;
        $refund->reason = $request->reason;
        $refund->admin_approval = 0;
        $refund->admin_seen = 0;
        $refund->resone_id = $request->resone_id;
        $refund->refund_amount = $order_detail->price + $order_detail->tax;
        $refund->refund_status = 0;
        if ($refund->save()) {
            flash("Refund Request has been sent successfully")->success();
            return redirect()->route('purchase_history.index');
        } else {
            flash("Something went wrong")->error();
            return back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendor_index()
    {
        $refunds = RefundRequest::where('seller_id', Auth::user()->id)->latest()->paginate(10);
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('refund_request.frontend.recieved_refund_request.index', compact('refunds'));
        } else {
            return view('refund_request.frontend.recieved_refund_request.index', compact('refunds'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customer_index()
    {
        $refunds = RefundRequest::where('user_id', Auth::user()->id)->latest()->paginate(10);
        return view('refund_request.frontend.refund_request.index', compact('refunds'));
    }

    //Set the Refund configuration
    public function refund_config()
    {
        return view('refund_request.config');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refund_time_update(Request $request)
    {
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        if ($business_settings != null) {
            $business_settings->value = $request->value;
            $business_settings->save();
        } else {
            $business_settings = new BusinessSetting;
            $business_settings->type = $request->type;
            $business_settings->value = $request->value;
            $business_settings->save();
        }
        flash("Refund Request sending time has been updated successfully")->success();
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refund_sticker_update(Request $request)
    {
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        if ($business_settings != null) {
            $business_settings->value = $request->logo;
            $business_settings->save();
        } else {
            $business_settings = new BusinessSetting;
            $business_settings->type = $request->type;
            $business_settings->value = $request->logo;
            $business_settings->save();
        }
        flash("Refund Sticker has been updated successfully")->success();
        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_index()
    {
        $refunds = RefundRequest::where('refund_status', 0)->latest()->paginate(15);
        // return $refunds[0]->orderDetail->product->slug_ar;
        return view('refund_request.index', compact('refunds'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paid_index()
    {
        $refunds = RefundRequest::where('refund_status', 1)->latest()->paginate(15);
        return view('refund_request.paid_refund', compact('refunds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function request_approval_vendor(Request $request)
    {
        $refund = RefundRequest::findOrFail($request->el);
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            $refund->seller_approval = 1;
            $refund->admin_approval = 1;
        } else {
            $refund->seller_approval = 1;
        }

        if ($refund->save()) {
            $cleint = User::find($refund->user_id);
            if ($cleint->device_token != null) {
                if ($cleint->lang = 'ar') {
                    $notification_title = 'قبول الأسترجاع';
                    $notification_text = 'تم قبول استرجاع المنتج';
                } else {
                    $notification_title = 'Refund Approved';
                    $notification_text = 'Your Refund has been aproved.';
                }
                $notification_body['reciever_id'] = $cleint->id;
                $notification_body['type'] = 'refund';
                $notification_body['order_id'] = $refund->id;
                $notification_body['title'] = $notification_title;
                $notification_body['text'] = $notification_text;
                $notification_body['body'] = $notification_text;
                $notification_body['click_action'] = 'MedicalApp';
                $notification_body['sound'] = true;
                $notification_body['icon'] = 'logo';
                $notification_body['android_channel_id'] = 'android_channel_id';
                $notification_body['high_priority'] = 'high_priority';
                $notification_body['show_in_foreground'] = true;
                sendNotification($notification_body, $cleint->device_token);
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function getRefunded(Request $request)
    {
        // return $request;
        $refund = RefundRequest::findOrFail($request->id);
        $user = User::findOrFail($refund->user_id);
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            $refund->admin_approval = 1;
            $refund->refund_status = 1;
        }
        if ($request->seller_approval == 1) {
            $seller = Seller::where('user_id', $request->seller_id)->first();
            if ($seller != null) {
                $seller->admin_to_pay -= $request->amount;
            }
            $seller->save();
        }
        if ($request->withshipment == "on") {
            $refund->refund_amount = $request->amount - $request->coupon_discount - $request->shipping_cost;
        } else {
            $refund->refund_amount = $request->amount - $request->coupon_discount;
        }
        if ($request->payment_type == 'fawry' || $request->payment_type == 'cash_on_delivery') {
            $wallet = new Wallet;
            $wallet->user_id = $refund->user_id;
            $wallet->amount = $refund->refund_amount;
            $wallet->approval = 1;
            $wallet->payment_method = 'Refund';
            $wallet->payment_details = 'Product Money Refund';
            $wallet->save();
            $user->balance += $refund->refund_amount;
            $user->save();
        }

        if ($refund->save()) {
            if ($user->device_token != null) {
                if ($user->lang = 'ar') {
                    $notification_title = 'أسترجاع الأموال';
                    $notification_text = 'تم أضافة مبلغ المنج المسترجع الى محفظتك';
                } else {
                    $notification_title = 'Refund Payment';
                    $notification_text = 'Product Money Refunded.';
                }
                $notification_body['reciever_id'] = $user->id;
                $notification_body['type'] = 'refund';
                $notification_body['order_id'] = $refund->id;
                $notification_body['title'] = $notification_title;
                $notification_body['text'] = $notification_text;
                $notification_body['body'] = $notification_text;
                $notification_body['click_action'] = 'MedicalApp';
                $notification_body['sound'] = true;
                $notification_body['icon'] = 'logo';
                $notification_body['android_channel_id'] = 'android_channel_id';
                $notification_body['high_priority'] = 'high_priority';
                $notification_body['show_in_foreground'] = true;
                sendNotification($notification_body, $user->device_token);
            }

            return redirect()->route('refund_requests_all');
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refund_pay()
    {
        $refund = RefundRequest::findOrFail($_GET['id']);
        return view('refund_request.take_refund_action', compact('refund'));
        return $refund;

        $wallet = new Wallet;
        $wallet->user_id = $refund->user_id;
        $wallet->amount = $refund->refund_amount;
        $wallet->payment_method = 'Refund';
        $wallet->payment_details = 'Product Money Refund';
        $wallet->save();
        $user = User::findOrFail($refund->user_id);
        $user->balance += $refund->refund_amount;
        $user->save();
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            $refund->admin_approval = 1;
            $refund->refund_status = 1;
        }
        if ($refund->save()) {
            if ($user->device_token != null) {
                if ($user->lang = 'ar') {
                    $notification_title = 'أسترجاع الأموال';
                    $notification_text = 'تم أضافة مبلغ المنج المسترجع الى محفظتك';
                } else {
                    $notification_title = 'Refund Payment';
                    $notification_text = 'Product Money Refunded.';
                }
                $notification_body['reciever_id'] = $user->id;
                $notification_body['type'] = 'refund';
                $notification_body['order_id'] = $refund->id;
                $notification_body['title'] = $notification_title;
                $notification_body['text'] = $notification_text;
                $notification_body['click_action'] = 'MedicalApp';
                $notification_body['sound'] = true;
                $notification_body['icon'] = 'logo';
                $notification_body['android_channel_id'] = 'android_channel_id';
                $notification_body['high_priority'] = 'high_priority';
                $notification_body['show_in_foreground'] = true;
                sendNotification($notification_body, $user->device_token);
            }
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function refund_request_send_page($id)
    {
        $order_detail = OrderDetail::findOrFail($id);
        $resons = RefundResone::select('id', 'resone_' . locale() . ' as resone')->get();
        if ($order_detail->product != null && $order_detail->product->refundable == 1) {
            return view('refund_request.frontend.refund_request.create', compact('order_detail', 'resons'));
        } else {
            return back();
        }
    }

    public function cancel_order_user($id)
    {

        $order = Order::findOrFail($id);
        $order->cancel_order = 1;
        $order->save();

        $cleint = User::find($order->user_id);
        if ($order->payment_status == 'paid') {
            $cleint->balance += $order->grand_total;
            $cleint->save();
        }
        if ($cleint->device_token != null) {
            if ($cleint->lang = 'ar') {
                $notification_title = 'طلبك تم تغيير حالتة';
                $notification_text = 'طلبك ' . $order->code . ' تم تحديث حالتة ألي ' . 'تم الألغاء';
            } else {
                $notification_title = 'Order status has been updated ';
                $notification_text = 'Your order ' . $order->code . ' status has been updated to canceled';
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
        return back();
    }

    /**
     * Show the form for view the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Shows the refund reason
    public function reason_view($id)
    {
        $refund = RefundRequest::findOrFail($id);
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            if ($refund->orderDetail != null) {
                $refund->admin_seen = 1;
                $refund->save();
                return view('refund_request.reason', compact('refund'));
            }
        } else {
            return view('refund_request.frontend.refund_request.reason', compact('refund'));
        }
    }
}
