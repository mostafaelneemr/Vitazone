<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerProductCollection extends ResourceCollection
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
                    'name' => $data->{'name_' . locale()},
                    'published' => $data->published,
                    'status' => $data->status,
                    'added_by' => $data->added_by,
                    'user' => [
                        'id' => ($data->user) ? $data->user->id : null,
                        'name' => ($data->user) ? $data->user->name : null,
                        'phone' => ($data->user) ? $data->user->phone : null,
                        'avatar' => ($data->user) ? $data->user->avatar : null,
                        'avatar_original' => ($data->user) ? $data->user->avatar_original : null,
                    ],
                    'category_id' => $data->category_id,
                    'subcategory_id' => $data->subcategory_id,
                    'subsubcategory_id' => $data->subsubcategory_id,
                    'brand_id' => $data->brand_id,
                    'description' => $data->{'description_' . locale()},
                    'photos' => json_encode($this->convertPhotos(explode(',', $data->photos))),
                    'thumbnail_img' => api_asset($data->thumbnail_img),
                    'location' => $data->{'location_' . locale()},
                    'video_provider' => $data->video_provider,
                    'video_link' => $data->video_link,
                    'unit' => $data->unit,
                    'conditon' => $data->conditon,
                    'tags' => $data->{'tags_' . locale()},
                    'unit_price' => $data->unit_price,
                    'unit_discount' => $data->unit_discount,
                    'meta_title' =>$data->{'meta_title_' . locale()},
                    'meta_description' => $data->{'meta_description_' . locale()},
                    'meta_img' => api_asset($data->meta_img),
                    'pdf' => api_asset($data->pdf),
                    'slug' => $data->{'slug_' . locale()},
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at
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

    protected function convertPhotos($data)
    {
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, api_asset($item));
        }
        return $result;
    }
}
