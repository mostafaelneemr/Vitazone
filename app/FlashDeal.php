<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FlashDeal extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    public function flash_deal_products()
    {
        return $this->hasMany( FlashDealProduct::class );
    }
}
