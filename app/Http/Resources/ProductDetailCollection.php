<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Review;
use App\Models\Attribute;
use App\Models\BusinessSetting;
use App\Models\Wishlist;

class ProductDetailCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => (int) $data->id,
                    'variant_product' => $data->variant_product,
                    'name' => $data->{'name_' . locale()},
                    'added_by' => $data->added_by,
                    'country' => $data->{'country_' . locale()},
                    'user' => [
                        'name' => $data->user->name,
                        'email' => $data->user->email,
                        'avatar' => $data->user->avatar,
                        'avatar_original' => $data->user->avatar_original,
                        'shop_name' => $data->added_by == 'admin' ? '' : $data->user->shop->name,
                        'shop_logo' => $data->added_by == 'admin' ? '' : $data->user->shop->logo,
                        'shop_link' => $data->added_by == 'admin' ? '' : route('shops.info', $data->user->shop->id)
                    ],
                    'brand' => [
                        'name' => $data->brand != null ? $data->brand->{'name_' . locale()} : null,
                        'logo' => $data->brand != null ?  api_asset($data->brand->logo) : null,
                        'links' => [
                            'products' => $data->brand != null ? route('api.products.brand', $data->brand_id) : null
                        ]
                    ],
                    'photos' => $this->convertPhotos(explode(',', $data->photos)),
                    'video_provider' => $data->video_provider,
                    'video_link' => $data->video_link,
                    'pdf' => api_asset($data->pdf),
                    'thumbnail_image' => api_asset($data->thumbnail_img),
                    'tags' => explode(',', $data->tags),
                    'base_price' => (float) round(homeBasePrice($data->id), BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'price_lower' => (float) round(explode('-', homeDiscountedPrice($data->id))[0], BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'price_higher' => (float) round(explode('-', homeDiscountedPrice($data->id))[1], BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'base_discounted_price' => (float) round(homeDiscountedBasePrice($data->id), BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'choice_options' => $this->convertToChoiceOptions(json_decode($data->choice_options)),
                    'colors' => json_decode($data->colors),
                    'todays_deal' => (int) $data->todays_deal,
                    'featured' => (int) $data->featured,
                    'is_favorite' => (auth('api')->check()) ? ((Wishlist::where(['product_id' => $data->id, 'user_id' => auth('api')->user()->id])->count() > 0) ? true : false) : false,
                    'current_stock' => (int) $data->current_stock,
                    'unit' => $data->unit,
                    'discount' => (float) round($data->discount, BusinessSetting::where('type', 'no_of_decimals')->first()->value),
                    'discount_type' => $data->discount_type,
                    'tax' => (float) $data->tax,
                    'tax_type' => $data->tax_type,
                    'shipping_type' => $data->shipping_type,
                    'shipping_cost' => (float) $data->shipping_cost,
                    'number_of_sales' => (int) $data->num_of_sale,
                    'rating' => (float) $data->rating,
                    'rating_count' => (int) Review::where(['product_id' => $data->id])->count(),
                    'description' => $data->{'description_' . locale()},
                    'links' => [
                        'reviews' => route('api.reviews.index', $data->id),
                        'related' => route('products.related', $data->id)
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

    protected function convertToChoiceOptions($data)
    {
        $result = array();
        foreach ($data as $key => $choice) {
            $item['name'] = $choice->attribute_id;
            $item['title'] = Attribute::select(['*', 'name_' . locale() . ' as name'])->find($choice->attribute_id)->name;
            $item['options'] = $choice->values;
            array_push($result, $item);
        }
        return $result;
    }

    protected function convertPhotos($data)
    {
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, api_asset($item));
        }
        return $result;
    }
}
