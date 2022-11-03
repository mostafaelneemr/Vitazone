<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo( user::class );
    }
}
