<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PurchaseHistoryCollection;
use App\Models\Order;
use Illuminate\Http\Request;

class PurchaseHistoryController extends Controller
{
    public function index($id)
    {
        return new PurchaseHistoryCollection(Order::where('user_id', $id)->latest()->get());
    }
    public function trackOrder(Request $request)
    {
        $request->validate( [
            'order_code' => 'required'
        ] );
        $order = \App\Order::where('code', $request->order_code)->first();

        if(empty($order)){
            return response()->json( ['status' => false, 'message' => trans('messages.Verification code mismatch')], 200 );
        }
        return response()->json( ['status' => true, 'order_status' =>ucfirst(str_replace('_', ' ', $order->orderDetails[0]->delivery_status))], 200 );
    }
}
