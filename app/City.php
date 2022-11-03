<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'province_id',
        'name_en',
        'name_ar',
        'status',
        'shipping_cost',
        'shipping_cost_high',
        'shipping_duration',
        'shipping_duration_high',
        'code',
    ];

    public function province()
    {
        return $this->belongsTo('App\Provinces','province_id','id');
    }
    public function regions()
    {
        return $this->hasMany('App\Region','city_id','id');
    }

    public function adresses()
    {
        return $this->hasMany(Address::class,'city');
    }
}
