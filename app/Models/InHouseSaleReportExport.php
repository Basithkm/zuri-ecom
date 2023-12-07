<?php

namespace App\Models;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InHouseSaleReportExport implements FromCollection, WithHeadings
{

    public function collection()
    {
        return Product::select('name','num_of_sale')->get();
    }

     public function headings(): array
    {
        return [
            'name',
            'num_of_sale',
        ];
    }

}
