<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\OTPVerificationController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\AffiliateController;
use App\Order;
use App\Product;
use App\Color;
use App\OrderDetail;
use App\CouponUsage;
use App\OtpConfiguration;
use App\User;
use App\BusinessSetting;
use Auth;
use Session;
use DB;
use PDF;
use Mail;
use App\Mail\InvoiceEmailManager;
use CoreComponentRepository;
use function GuzzleHttp\Promise\all;
use MPDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', auth('api')->user()->id)
                    ->select('orders.*', 'order_details.*')
                    ->distinct();

        if ($request->payment_status != null){
            $orders = $orders->where('order_details.payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = \App\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return response()->json([
            'orders' => $orders
        ]);
    }


    public function order_details(Request $request)
    {
        $order = Order::select('orders.*','order_details.*')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', auth('api')->user()->id)
                    ->where('orders.id', '=', $request->order_id)->first();

        return response()->json([
            'order' => $order
        ]);
    }

    public function update_seller_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $user = \App\User::find($user_id);
        if($user->user_type == 'seller'){
            foreach($order->orderDetails->where('seller_id', auth('api')->user()->id) as $key => $orderDetail){
                $orderDetail->seller_status = $request->status;
                $orderDetail->save();
            }
        }
        return response()->json([
            'status' => true
        ]);
    }
}
