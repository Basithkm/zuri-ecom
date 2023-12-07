<?php

namespace App\Models;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockReportExport implements FromCollection, WithHeadings
{
     public function collection()
    {
         return Product::select('name')
            ->withSum('stocks', 'qty') 
            ->get();
    }

     public function headings(): array
    {
        return [
            'name',
            'stock',
        ];
    }
 
}
