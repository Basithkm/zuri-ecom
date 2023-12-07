<?php

namespace App\Models;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
Use App\Models\Wishlist;

class WishListReportExport implements FromCollection, WithHeadings
{
      public function collection()
    {
      
     $products = Product::with('wishlists')->get();

     $data = $products->map(function ($product) {
        return [
            'name' => $product->name,
            'wishlists' => $product->wishlists->count(),
        ];
    });

    return $data;
    }

     public function headings(): array
    {
        return [
            'name',
            'Number of wish',
        ];
    }
    
}
  