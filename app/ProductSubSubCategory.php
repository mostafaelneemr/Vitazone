<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSubSubCategory extends Model
{
    protected static $logAttributes = ['*'];
    protected $fillable = ['subsubcategory_id','product_id'];
}
