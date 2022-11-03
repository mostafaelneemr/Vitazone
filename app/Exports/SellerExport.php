<?php

namespace App\Exports;

use App\Seller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SellerExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Seller::all();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'Email',
            'Num Of Products',

        ];
    }

    public function map($seller): array
    {
        return [
            ($seller->user) ? $seller->user->name : null,
            ($seller->user) ? $seller->user->phone : null,
            ($seller->user) ? $seller->user->email : null,
            ($seller->user) ? \App\Product::where('user_id', $seller->user->id)->count() : null,

        ];
    }
}
