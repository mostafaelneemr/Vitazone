<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        //dd($this);
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id'=>$data->id,
                    'name' => $data->name,
                    'banner' => api_asset($data->banner),
                    'icon' => api_asset($data->icon),
                    'tag_ids' => $data->tag_ids,
                    'sub_categories' =>$data->subCategories,

//                    'subCategories'=>new SubCategoryCollection($data->subCategories),
                  // 'subCategories'=>$data->subCategories()->select(['id','category_id','slug','name_'.locale().' as name'])->get(),
                    'links' => [
                        'products' => route('api.products.category', $data->id),
//                        'sub_categories' => route('subCategories.index', $data->id)
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
