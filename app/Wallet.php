<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Wallet extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'payment_details',
        'approval',
        'offline_payment',
        'reciept',
        'fawry_ref_num'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
