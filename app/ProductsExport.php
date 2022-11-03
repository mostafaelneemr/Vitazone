<?php

namespace App;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Product::all(['*','name_'.locale().' as name']);
    }

    public function headings(): array
    {
        return [
            'categories',
            'country_ar',
            'country_en',
            'light_heavy_shipping',
            'name_en',
            'name_ar',
            'slug_en',
            'slug_ar',
            'description_ar',
            'description_en',            
            'tags_en',
            'tags_ar',
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
            implode(',',array_column($product->subsubcategoryMany->toArray(),'id')),
            $product->country_ar,
            $product->country_en,
            $product->light_heavy_shipping,
            $product->name_en,
            $product->name_ar,
            $product->slug_en,
            $product->slug_ar,
            $product->description_ar,
            $product->description_en,
            $product->tags_en,
            $product->tags_ar,
            $product->brand_id,
            $product->video_provider,
            $product->video_link,
            $product->unit_price,
            $product->purchase_price,
            $product->unit,
            $product->current_stock,
            $product->meta_title_en,
            $product->meta_title_ar,
            $product->meta_description_en,
            $product->meta_description_ar,
            
        ];
    }
}
