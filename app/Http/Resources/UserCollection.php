<?php

namespace App\Http\Resources;

use App\Country;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => (integer) $data->id,
                    'name' => $data->name,
                    'type' => $data->user_type,
                    'email' => $data->email,
                    'avatar' => $data->avatar,
                    'avatar_original' => $data->avatar_original,
                    'address' => $data->address,
                    'city' => $data->city,
                    'country' => Country::where('id', $data->country)->value('name_'.locale()),
                    'phone' => $data->phone
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
