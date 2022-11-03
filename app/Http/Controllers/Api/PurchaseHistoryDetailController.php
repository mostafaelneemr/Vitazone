<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PurchaseHistoryDetailCollection;
use App\Models\OrderDetail;
use App\Order;
use Illuminate\Support\Facades\Request;

class PurchaseHistoryDetailController extends Controller
{
    public function index($id)
    {
        return new PurchaseHistoryDetailCollection( OrderDetail::where( 'order_id', $id )->with(['order'=>function($query){
            $query->where('user_id',auth()->id());
        }])->get() );
    }
    public function unpaidOrders()
    {
        return new PurchaseHistoryDetailCollection( OrderDetail::where( 'payment_status', 'unpaid' )->whereHas('order',function ($query){
            $query->where('user_id',auth()->id());
        })->get() );
    }
    public function paidOrders()
    {
        return new PurchaseHistoryDetailCollection( OrderDetail::where( 'payment_status', 'paid' )->whereHas('order',function($query){
            $query->where('user_id',auth()->id());
        })->get() );
    }  public function toBeShippedOrders()
    {
        return new PurchaseHistoryDetailCollection( OrderDetail::where( 'delivery_status', 'pending' )->whereHas('order',function($query){
            $query->where('user_id',auth()->id());
        })->get() );
    } public function shippedOrders()
    {
        return new PurchaseHistoryDetailCollection( OrderDetail::where( 'delivery_status', 'on_delivery' )->whereHas('order',function($query){
            $query->where('user_id',auth()->id());
        })->get() );
    }
}
