<?php

namespace App;

use App\Product;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Auth;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // return $row;
        return new Product([
           'country_ar'     => $row['country_ar'],
           'country_en'     => $row['country_en'],
           'light_heavy_shipping'     => $row['light_heavy_shipping'],
           'name_en'     => $row['name_en'],
           'name_ar'     => $row['name_ar'],
           'description_ar'     => $row['description_ar'],
           'description_en'     => $row['description_en'],
           'tags_en'     => $row['tags_en'],
           'tags_ar'     => $row['tags_ar'],
           'added_by'    => Auth::user()->user_type == 'seller' ? 'seller' : 'admin',
           'published'    => Auth::user()->user_type == 'seller' ? 0 : 1,
           'user_id'    => Auth::user()->user_type == 'seller' ? Auth::user()->id : User::where('user_type', 'admin')->first()->id,
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
           'slug_en' => $row['slug_en'],
           'slug_ar' => $row['slug_ar'],
           'xls_categories' => $row['categories'],
        ]);
    }

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
