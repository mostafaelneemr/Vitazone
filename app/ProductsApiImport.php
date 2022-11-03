<?php

namespace App;

use App\Product;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Auth;

class ProductsApiImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Product([
           'name_en'     => $row['name_en'],
           'name_ar'     => $row['name_ar'],
           'added_by'    => auth('api')->user()->user_type == 'seller' ? 'seller' : 'admin',
           'user_id'    => auth('api')->user()->user_type == 'seller' ? auth('api')->user()->id : User::where('user_type', 'admin')->first()->id,
           'category_id'    => $row['category_id'],
           'subcategory_id'    => $row['subcategory_id'],
           'subsubcategory_id'    => $row['subsubcategory_id'],
           'brand_id'    => $row['brand_id'],
           'video_provider'    => $row['video_provider'],
           'video_link'    => $row['video_link'],
           'unit_price'    => $row['unit_price'],
           'purchase_price'    => $row['purchase_price'],
           'unit'    => $row['unit'],
           'current_stock' => $row['current_stock'],
           'meta_title_en' => $row['meta_title_en'],
           'meta_title_ar' => $row['meta_title_ar'],
           'meta_description_en' => $row['meta_description_en'],
           'meta_description_ar' => $row['meta_description_ar'],
           'colors' => json_encode(array()),
           'choice_options' => json_encode(array()),
           'variations' => json_encode(array()),
           'slug_en' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $row['name_en'])).'-'.Str::random(5),
           'slug_ar' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $row['name_ar'])).'-'.Str::random(5),
        ]);    }

    public function rules(): array
    {
        return [
             // Can also use callback validation rules
             'unit_price' => function($attribute, $value, $onFailure) {
                  if (!is_numeric($value)) {
                       $onFailure('Unit price is not numeric');
                  }
              }
        ];
    }
}
