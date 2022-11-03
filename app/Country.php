<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Country extends Model
{
    use LogsActivity;
    protected $fillable = [
        'code',
        'name_en',
        'name_ar',
        'shipping_cost',
        'status',
        'shipping_cost_high',
        'shipping_duration',
        'shipping_duration_high',
    ];
    protected static $logAttributes = ['*'];
    protected $guarded = [];

    public function cities()
    {
        return $this->hasMany('App\City','country_id','id');
    }

    public function adresses()
    {
        return $this->hasMany(Address::class,'country');
    }
}
