<?php

namespace App\Exports;

use App\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::all();
    }
    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'Email',

        ];
    }

    public function map($customer): array
    {
        return [
            ($customer->user) ? $customer->user->name : null,
            ($customer->user) ? $customer->user->phone : null,
            ($customer->user) ? $customer->user->email : null,

        ];
    }
}
