<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellerPackage extends Model
{
    protected $guarded = [];

    public function seller_package_payments()
    {
        return $this->hasMany(SelllerPackagePayment::class);
    }

}
