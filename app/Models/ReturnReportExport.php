<?php

namespace App\Models;
use App\Models\OrderDetail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReturnReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
         return OrderDetail::select('updated_at','order_id','product_id','quantity','price')->get();
    }

     public function headings(): array
    {
        return [
           'Date',
           'Order Id',
           'Product Id',
           'Quantity',
           'Price',
        ];
    }
}
