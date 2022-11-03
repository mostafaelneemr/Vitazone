<?php

namespace App\Http\Resources;

use App\Models\BusinessSetting;
use App\Models\Wishlist;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->{'name_' . locale()},
                    'thumbnail_image' => api_asset($data->thumbnail_img),
                    'base_price' => (float) round(homeBasePrice($data->id), BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'base_discounted_price' => (float) round(homeDiscountedBasePrice($data->id), BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'rating' => (float) $data->rating,
                    'current_stock' => $data->current_stock,
                    'is_favorite' => (auth('api')->check()) ? ((Wishlist::where(['product_id' => $data->id, 'user_id' => auth('api')->user()->id])->count() > 0) ? true : false) : false,
                    'links' => [
                        'details' => route('products.show', $data->id),
                        'reviews' => route('api.reviews.index', $data->id),
                        'related' => route('products.related', $data->id),
                        'top_from_seller' => route('products.topFromSeller', $data->id)
                    ]
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
