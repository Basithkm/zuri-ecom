<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class ProductMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $wholesale_product = ($data->wholesale_product == 1) ? true : false;
                $createdAt = Carbon::parse($data->created_at);
                $isNewProduct = $createdAt->isToday() || $createdAt->diffInDays() <= 10;


                $photo_paths = get_images_path($data->photos);

                $photos = [];

                

                if (!empty($photo_paths)) {
                    for ($i = 0; $i < count($photo_paths); $i++) {
                        if ($photo_paths[$i] != "") {
                            $item = array();
                            $item['variant'] = "";
                            $item['path'] = $photo_paths[$i];
                            $photos[] = $item;
                        }
                    }
                }

                foreach ($data->stocks as $stockItem) {
                    if ($stockItem->image != null && $stockItem->image != "") {
                        $item = array();
                        $item['variant'] = $stockItem->variant;
                        $item['path'] = uploaded_asset($stockItem->image);
                        $photos[] = $item;
                    }
                }




                
                return [
                    'id' => $data->id,
                    'name' => $data->getTranslation('name'),
                    'thumbnail_image' => uploaded_asset($data->thumbnail_img),
                    'photos' => $photos,
                    'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                    'discount' => "-" . discount_in_percentage($data) . "%",
                    'stroked_price' => home_base_price($data),
                    'main_price' => home_discounted_base_price($data),
                    'rating' => (float) $data->rating,
                    'sales' => (int) $data->num_of_sale,
                    'is_wholesale' => $wholesale_product,
                    'new_product' => $isNewProduct,
                    'product_type' => $data->product_type,
                    'cash_on_delivery' => $data->cash_on_delivery,
                    'festive_collection' => $data->festive_collection,
                    'best_seller' => $data->best_seller,
                    'category_id' => $data->category_id,
                    'custom' => $data->custom,
                    'links' => [
                        'details' => route('products.show', $data->id),
                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
