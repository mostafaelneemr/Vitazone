<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'city_id',
        'name_en',
        'name_ar',
        'status',
        'shipping_cost',
        'shipping_cost_high',
        'shipping_duration',
        'shipping_duration_high',
        'code',
    ];

    public function city()
    {
        return $this->belongsTo('App\City','city_id','id');
    }

    public function adresses()
    {
        return $this->hasMany(Address::class,'region');
    }
}
