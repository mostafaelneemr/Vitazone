<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{

    protected $fillable = [
        'name',
        'logo',
        'currency_id',
        'currency_format',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'google_plus',
        'seller_policy',
        'return_policy',
        'support_policy',

        'promotion_title_ar',
        'promotion_title_en',
        'promotion_desc_ar',
        'promotion_desc_en',
        'promotion_photo',
        'promotion_link_en',
        'promotion_link_ar',
        'promotion_appear',
    ];
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
