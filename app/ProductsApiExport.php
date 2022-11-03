<?php

namespace App;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsApiExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Product::select(['*','name_'.locale().' as name'])->where('user_id', auth('api')->user()->id)->get();
    }

    public function headings(): array
    {
        return [
            'name_en',
            'name_ar',
            'added_by',
            'user_id',
            'category_id',
            'subcategory_id',
            'subsubcategory_id',
            'brand_id',
            'video_provider',
            'video_link',
            'unit_price',
            'purchase_price',
            'unit',
            'current_stock',
            'meta_title_en',
            'meta_title_ar',
            'meta_description_en',
            'meta_description_ar',
        ];
    }

    /**
    * @var Product $product
    */
    public function map($product): array
    {
        return [
            $product->name_en,
            $product->name_ar,
            $product->added_by,
            $product->user_id,
            $product->category_id,
            $product->subcategory_id,
            $product->subsubcategory_id,
            $product->brand_id,
            $product->video_provider,
            $product->video_link,
            $product->unit_price,
            $product->purchase_price,
            $product->unit,
            $product->current_stock,
        ];
    }
}
