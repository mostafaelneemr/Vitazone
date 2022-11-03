<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $fillable = [
        'country_id',
        'name_en',
        'name_ar',
        'status',
        'code',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }
    public function cities()
    {
        return $this->hasMany('App\City', 'province_id', 'id');
    }

    public function adresses()
    {
        return $this->hasMany(Address::class, 'province');
    }
}
