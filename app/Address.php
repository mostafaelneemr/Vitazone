<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'country',
        'province',
        'city',
        'region',
        'postal_code',
        'phone',
        'set_default'
    ];
    use LogsActivity;
    protected static $logAttributes = ['*'];
   public function addressCountry()
   {
       return $this->belongsTo(Country::class,'country');
   }

   public function addressProvince()
   {
       return $this->belongsTo(Provinces::class,'province');
   }

   public function addressCity()
   {
       return $this->belongsTo(City::class,'city');
   }

   public function addressRegion()
   {
       return $this->belongsTo(Region::class,'region');
   }
}
