<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];
    protected $fillable = [
        'xls_categories', 'light_heavy_shipping', 'description_ar', 'meta_title_en', 'meta_title_ar', 'meta_description_en', 'meta_description_ar', 'tags_en', 'tags_ar', 'description_en', 'name_ar', 'name_en', 'added_by', 'user_id', 'brand_id', 'video_provider', 'video_link', 'unit_price',
        'purchase_price', 'unit', 'categories', 'slug_ar', 'slug_en', 'colors', 'choice_options', 'variations', 'current_stock', 'country_ar', 'country_en'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subsubcategoryMany()
    {
        return $this->belongsToMany(Category::class, 'product_sub_sub_categories', 'product_id', 'subsubcategory_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function flash_deal_product()
    {
        return $this->hasOne(FlashDealProduct::class);
    }
}
