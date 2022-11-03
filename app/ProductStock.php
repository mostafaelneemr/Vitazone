<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductStock extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    public function product()
    {
        return $this->belongsTo( Product::class );
    }
}
