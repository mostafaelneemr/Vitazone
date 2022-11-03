<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];


    public function users()
    {
        $this->hasMany(User::class);
    }
}
