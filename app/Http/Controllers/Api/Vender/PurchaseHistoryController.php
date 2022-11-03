<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Order;
use App\OrderDetail;
use DB;

class PurchaseHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index()
    {
        $orders = Order::where('user_id', auth('api')->user()->id)->orderBy('code', 'desc')->paginate(9);
        return response()->json([
            'orders' => $orders
        ]);
    }

    public function digital_index()
    {
        $orders = DB::table('orders')
                        ->orderBy('code', 'desc')
                        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->join('products', 'order_details.product_id', '=', 'products.id')
                        ->where('orders.user_id', auth('api')->user()->id)
                        ->where('products.digital', '1')
                        ->where('order_details.payment_status', 'paid')
                        ->select('order_details.id')
                        ->paginate(1);
        return response()->json([
            'orders' => $orders
        ]);
    }

    public function purchase_history_details(Request $request)
    {
        $order = Order::find($request->order_id);
        if($order != null){
            $order->delivery_viewed = 1;
            $order->payment_status_viewed = 1;
            $order->save();
            return response()->json([
                'order' => $order,
                'success' => true
            ]);
        }

        return response()->json([
                'error' => "Order not found"
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
