<?php

namespace App\Http\Resources;

use App\Models\BusinessSetting;
use App\Models\Wishlist;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductListingCollection extends ResourceCollection
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
                    'variant_product' => $data->variant_product,
                    'name' => $data->{'name_' . locale()},
                    'country' => $data->{'country_' . locale()},
                    'description' => $data->{'description_' . locale()},
                    'photos' => explode(',', $data->photos),
                    'thumbnail_image' => api_asset($data->thumbnail_img),
                    'base_price' => (float) round(homeBasePrice($data->id), BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'base_discounted_price' => (float) round(homeDiscountedBasePrice($data->id), BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'todays_deal' => (int) $data->todays_deal,
                    'featured' => (int) $data->featured,
                    'is_favorite' => (auth('api')->check()) ? ((Wishlist::where(['product_id' => $data->id, 'user_id' => auth('api')->user()->id])->count() > 0) ? true : false) : false,
                    'unit' => $data->unit,
                    'current_stock' => $data->current_stock,
                    'tags' => $data->tags,
                    'hashtag_ids' => $data->hashtag_ids,
                    'discount' => (float) round($data->discount, BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'discount_type' => $data->discount_type,
                    'rating' => (float) $data->rating,
                    'sales' => (int) $data->num_of_sale,
                    'links' => [
                        'details' => route('products.show', $data->id),
                        'reviews' => route('api.reviews.index', $data->id),
                        'related' => route('products.related', $data->id),
                        'top_from_seller' => route('products.topFromSeller', $data->id)
                    ]
                ];
            }),
            'pagination' =>  [
                'per_page' => 10,
                'count' => $this->count(),
                'total' => $this->total(),
                'prev'  => $this->previousPageUrl(),
                'next'  => $this->nextPageUrl(),
            ]
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
