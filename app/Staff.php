<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Staff extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function pick_up_point()
    {
        return $this->hasOne(PickupPoint::class);
    }
}
