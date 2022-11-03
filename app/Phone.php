<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model 
{
    protected $fillable = [
        'user_id',
        'phone',
        'v_code',
        'status',
        'attempts_num'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
