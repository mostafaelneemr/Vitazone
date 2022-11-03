<?php

namespace App;

use App\Phone;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PhoneExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Phone::all(['*']);
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'phone',
            'status'
        ];
    }

    /**
    * @var Product $product
    */
    public function map($phone): array
    {
        return [
            $phone->id,
            ($phone->user) ? $phone->user->name : '--',
            $phone->phone,
            $phone->status
        ];
    }
}
