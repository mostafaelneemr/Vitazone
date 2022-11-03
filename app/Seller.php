<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Seller extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function payments()
    {
        return $this->hasMany( Payment::class );
    }
}
