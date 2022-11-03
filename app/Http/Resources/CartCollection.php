<?php

namespace App\Http\Resources;

use App\Models\BusinessSetting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => $data->id,
                    'product' => [
                        'id' => $data->product_id,
                        'name' => $data->product->{'name_'.locale()},
                        'image' => api_asset($data->product->thumbnail_img)
                    ],
                    'variation' => $data->variation,
                    'price' => (float) round($data->price, BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'tax' => (float) round($data->tax, BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'shipping_cost' => (float) round($data->shipping_cost, BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'quantity' => (integer) $data->quantity,
                    'date' => $data->created_at->diffForHumans()
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
