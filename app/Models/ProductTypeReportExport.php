<?php
namespace App\Models;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTypeReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
         return Product::select('user_id','product_type','mrp','created_at')->get();
    }

     public function headings(): array
    {
        return [
           'Date',
           'product_type',
           'Amount',
           'Date',
        ];
    }


}
