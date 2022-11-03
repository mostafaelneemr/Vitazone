<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HomeCategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {

        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->{'name_' . locale()},
                    'banner' => api_asset($data->banner),
                    'icon' => api_asset($data->icon),
                    'links' => [
                        'products' => route('api.products.category', $data->id),
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
