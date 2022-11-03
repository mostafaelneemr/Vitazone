<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
    	$product_count = count(\App\Product::where('user_id', auth('api')->user()->id)->get());
    	$total_sale = count(\App\OrderDetail::where('seller_id', auth('api')->user()->id)->where('delivery_status', 'delivered')->get());
    	$orderDetails = \App\OrderDetail::where('seller_id', auth('api')->user()->id)->get();
        $total_earning = 0;
        foreach ($orderDetails as $key => $orderDetail) {
            if($orderDetail->order->payment_status == 'paid'){
                $total_earning += $orderDetail->price;
            }
        }
        $successful_orders = count(\App\OrderDetail::where('seller_id', auth('api')->user()->id)->where('delivery_status', 'delivered')->get());
        $total_orders = count(\App\OrderDetail::where('seller_id', auth('api')->user()->id)->get());
        $pending_orders = count(\App\OrderDetail::where('seller_id', auth('api')->user()->id)->where('delivery_status', 'pending')->get());
        $cancelled_orders = count(\App\OrderDetail::where('seller_id', auth('api')->user()->id)->where('delivery_status', 'cancelled')->get());

        return response()->json([
            'product_count' => $product_count,
            'total_sale' => $total_sale,
            'orderDetails' => $orderDetails,
            'total_earning' => $total_earning,
            'successful_orders' => $successful_orders,
            'total_orders' => $total_orders,
            'pending_orders' => $pending_orders,
            'cancelled_orders' => $cancelled_orders
        ]);
    }
}
