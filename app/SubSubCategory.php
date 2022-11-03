<?php

namespace App;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SubSubCategory extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected $guarded = [];

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'subsubcategory_id');
    }
    
    public function classified_products()
    {
        return $this->hasMany(CustomerProduct::class, 'subsubcategory_id');
    }
}
