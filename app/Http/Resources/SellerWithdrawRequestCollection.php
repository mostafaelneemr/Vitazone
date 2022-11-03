<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SellerWithdrawRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'user_id' => $data->user_id,
                    'amount' =>  $data->amount,
                    'message' =>  $data->message,
                    'status' =>  $data->status,
                    'viewed' =>  $data->viewed,
                    'created_at' =>  $data->created_at,
                    'updated_at' =>  $data->updated_at,
                    'balance' =>  $data->user->balance
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
