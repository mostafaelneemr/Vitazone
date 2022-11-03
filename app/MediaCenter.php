<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaCenter extends Model
{
    protected $fillable = [
        'alt_ar',
        'alt_en',
        'file_path',
        'type'
    ];
}
