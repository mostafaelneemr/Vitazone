<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Shop extends Model
{
    use LogsActivity;
    protected $guarded =[];
    protected static $logAttributes = ['*'];

    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
