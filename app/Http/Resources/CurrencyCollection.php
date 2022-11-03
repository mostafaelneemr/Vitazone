<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CurrencyCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'name' => $data->{'name_'.locale()},
                    'code' => $data->code,
                    'symbol' => $data->{'symbol_'.locale()},
                    'exchange_rate' => (double) $data->exchange_rate
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
