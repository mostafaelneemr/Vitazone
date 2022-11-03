<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RefundRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'success' => true
        ];
    }
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'seller_approval' => $data->seller_approval,
                    'admin_approval' => $data->admin_approval,
                    'refund_amount' => $data->refund_amount,
                    'reason' => $data->reason,
                    'admin_reason' => $data->refundResone->{'resone_' . locale()},
                    'admin_seen' => $data->admin_seen,
                    'refund_status' => $data->refund_status,
                    'created_at' => $data->created_at,
                    'order_code' => $data->order->code,
                    'price' => $data->orderDetail->price,
                    'product_name' => ($data->orderDetail->product) ? $data->orderDetail->product->{'name_' . locale()} : ''
                ];
            })
        ];
    }
}
