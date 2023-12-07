<?php

namespace App\Models;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportExport implements FromCollection, WithHeadings
{
     public function collection()
    {
         return Order::select('created_at','code','user_id','grand_total')->get();
    }

     public function headings(): array
    {
        return [
           'Date',
           'order code',
           'customer Name',
           'paid amount',
        ];
    }
}
