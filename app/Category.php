<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;
    protected $guarded = [];
    protected static $logAttributes = ['*'];
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function subsubcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function productsMany()
    {
        return $this->belongsToMany(Product::class, 'product_sub_sub_categories', 'subsubcategory_id', 'product_id');
    }
    public function classified_products()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('categories');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
