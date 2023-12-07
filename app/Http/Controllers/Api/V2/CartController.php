<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Models\Color;
use App\Models\CustomValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function summary()
    {
        //$user = User::where('id', auth()->user()->id)->first();
        $items = auth()->user()->carts;
        if ($items->isEmpty()) {
            return response()->json([
                'sub_total' => format_price(0.00),
                'tax' => format_price(0.00),
                'shipping_cost' => format_price(0.00),
                'discount' => format_price(0.00),
                'grand_total' => format_price(0.00),
                'grand_total_value' => 0.00,
                'coupon_code' => "",
                'coupon_applied' => false,
            ]);
        }

        $sum = 0.00;
        $subtotal = 0.00;
        $tax = 0.00;       
        foreach ($items as $cartItem) {
            $item_sum = 0.00;
            $item_sum += ($cartItem->price + $cartItem->tax) * $cartItem->quantity;
            $item_sum += $cartItem->shipping_cost - $cartItem->discount;
            $sum +=  $item_sum  ;   //// 'grand_total' => $request->g

            $subtotal += $cartItem->price * $cartItem->quantity;
            $tax += $cartItem->tax * $cartItem->quantity;
        }




        return response()->json([
            'sub_total' => format_price($subtotal),
            'tax' => format_price($tax),
            'shipping_cost' => format_price($items->sum('shipping_cost')),
            'discount' => format_price($items->sum('discount')),
            'grand_total' => format_price($sum),
            'grand_total_value' => convert_price($sum),
            'coupon_code' => $items[0]->coupon_code,
            'coupon_applied' => $items[0]->coupon_applied == 1,
        ]);
    }




    public function count()
    {
        $items = auth()->user()->carts;

        return response()->json([
            'count' => sizeof($items),
            'status' => true,
        ]);
    }



    public function getList()
    {

        $owner_ids = Cart::where('user_id', auth()->user()->id)->select('owner_id')->groupBy('owner_id')->pluck('owner_id')->toArray();
        $currency_symbol = currency_symbol();
        $shops = [];
        if (!empty($owner_ids)) {
            foreach ($owner_ids as $owner_id) {
                $shop = array();
                $shop_items_raw_data = Cart::where('user_id', auth()->user()->id)->where('owner_id', $owner_id)->get()->toArray();
                $shop_items_data = array();
                if (!empty($shop_items_raw_data)) {
                    foreach ($shop_items_raw_data as $shop_items_raw_data_item) {
                        $product = Product::where('id', $shop_items_raw_data_item["product_id"])->first();
                        $shop_items_data_item["id"] = intval($shop_items_raw_data_item["id"]) ;
                        $shop_items_data_item["owner_id"] =intval($shop_items_raw_data_item["owner_id"]) ;
                        $shop_items_data_item["user_id"] =intval($shop_items_raw_data_item["user_id"]) ;
                        $shop_items_data_item["product_id"] =intval($shop_items_raw_data_item["product_id"]) ;
                        $shop_items_data_item["product_name"] = $product->getTranslation('name');
                        $shop_items_data_item["product_thumbnail_image"] = uploaded_asset($product->thumbnail_img);
                        $shop_items_data_item["variation"] = $shop_items_raw_data_item["variation"];
                        $shop_items_data_item["price"] =(double) cart_product_price($shop_items_raw_data_item, $product, false, false);
                        $shop_items_data_item["currency_symbol"] = $currency_symbol;
                        $shop_items_data_item["tax"] =(double) cart_product_tax($shop_items_raw_data_item, $product,false);
                        $shop_items_data_item["shipping_cost"] =(double) $shop_items_raw_data_item["shipping_cost"];
                        $shop_items_data_item["quantity"] =intval($shop_items_raw_data_item["quantity"]) ;
                        $shop_items_data_item["lower_limit"] = intval($product->min_qty) ;
                        $shop_items_data_item["upper_limit"] = intval($product->stocks->where('variant', $shop_items_raw_data_item['variation'])->first()->qty) ;
                        $shop_items_data[] = $shop_items_data_item;

                    }
                }

                $shop_data = Shop::where('user_id', $owner_id)->first();
                if ($shop_data) {
                    $shop['name'] = $shop_data->name;
                    $shop['owner_id'] =(int) $owner_id;
                    $shop['cart_items'] = $shop_items_data;
                } else {
                    $shop['name'] = "Inhouse";
                    $shop['owner_id'] =(int) $owner_id;
                    $shop['cart_items'] = $shop_items_data;
                }
                $shops[] = $shop;
            }
        }

        // dd($shops);   
        

        return response()->json($shops);
    }


    public function add(Request $request)
    { 


        $product = Product::findOrFail($request->id);

        $variant = $request->variant;
        $tax = 0;
        $custom_values = ['front_neck_depth','back_neck_depth','upper_bust','bust','waist','waist_round','lower_waist','hip','thigh_circumference','calf_circumference','knee_circumference','ankle_circumference','arm_hole','sleeve_length','sleeve_circumference','top_length','bottom_length','shoulder'];
        $custom_required = ['bust','waist_round','hip'];

       

        $rules = [];

        foreach ($custom_required as $key) {
            $rules[$key] = 'required';
        }
        $custom = $product->custom;
        $color = $request->color;

        
        if ($color && $variant) {
              $colorValue = Color::where('code', $color)->first();
              $colorName = $colorValue->name;
              $variant = $colorName . '-' . $variant;
        }else {
            if ($color) {
              $colorValue = Color::where('code', $color)->first();
              $colorName = $colorValue->name;
              $variant = $colorName; 
            }
        }
  
        
        // if ($custom) { 
        //     $customData = [];
        //     $validator = Validator::make($request->all(), $rules);


        //      $cartId = Cart::where('product_id', $request->id)->where('user_id', auth()->user()->id)->pluck('id')->first();

        //      $a= CustomValue::updateOrCreate(
        //     [
        //         'user_id' => auth()->user()->id,
        //         'cart_id' =>  $cartId,
        //         'front_neck_depth' =>  $request->front_neck_depth,
        //         'back_neck_depth' =>  $request->back_neck_depth,
        //         'upper_bust' =>  $request->upper_bust,
        //         'bust' =>  $request->bust,
        //         'waist' =>  $request->waist,
        //         'waist_round' =>  $request->waist_round,
        //         'lower_waist' =>  $request->lower_waist,
        //         'hip' =>  $request->hip,
        //         'thigh_circumference' =>  $request->thigh_circumference,
        //         'knee_circumference' =>  $request->knee_circumference,
        //         'calf_circumference' =>  $request->calf_circumference,
        //         'ankle_circumference' =>  $request->ankle_circumference,
        //         'arm_hole' =>  $request->arm_hole,
        //         'sleeve_length' =>  $request->sleeve_length,
        //         'sleeve_circumference' =>  $request->sleeve_circumference,
        //         'top_length' =>  $request->top_length,
        //         'bottom_length' =>  $request->bottom_length,
        //         'shoulder' =>  $request->shoulder,
        //         'order_notes' =>  $request->order_notes,

        //     ],

            
        //     // $customData
        // );

           
            
        //     if ($validator->fails()) {
        //         return response()->json(['errors' => $validator->errors()], 400);
        //     }
        //     foreach ($custom_values as $value) {
        //     //   if (empty($request->$value)) {
        //     //     return response()->json(['errors' => [$value =>'This field is required']], 400);
        //     //   }
        //       if (!is_numeric($request->$value) || intval($request->$value) != $request->$value) {
        //         return response()->json(['errors' => [$value => 'Value should be an integer']], 400);
        //        }
        //      $customData[$value] = (int)$request->$value;
        //     }
        // }
       
        $product_stock = $product->stocks->where('variant', $variant)->first();
        
        if (!$variant){
            $price = $product->unit_price;
        }
        else {
            $product_stock = $product->stocks->where('variant', $variant)->first();
            $price = $product_stock->price;
        }


       

        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        }
        elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        if ($product->min_qty > $request->quantity) {
            return response()->json(['result' => false, 'message' => translate("Minimum")." {$product->min_qty} ".translate("item(s) should be ordered")], 400);
        }

        $stock = $product->stocks->where('variant', $variant)->first()->qty;

        $variant_string = $variant != null && $variant != "" ? translate("for")." ($variant)" : "";
        if ($stock < $request->quantity && $product->digital == 0) {
            if ($stock == 0) {
                return response()->json(['result' => false, 'message' => "Stock out"], 404);
            } else {
                return response()->json(['result' => false, 'message' => translate("Only") ." {$stock} ".translate("item(s) are available")." {$variant_string}"], 404);
            }
        }

        $cart_item = Cart::where('product_id', $request->id)->where("user_id",auth()->id())->first();
        if($cart_item && isset($cart_item->product->digital) && $cart_item->product->digital== 1) {
            return response()->json(['result' => false, 'message' => 'Already added this product' ],400);
        }

        Cart::updateOrCreate([
            'user_id' => auth()->user()->id,
            'owner_id' => $product->user_id,
            'product_id' => $request->id,
            'variation' => $variant
        ], [
            'price' => $price,
            'tax' => $tax,
            'shipping_cost' => 0,
            'quantity' => DB::raw("quantity + $request->quantity")
        ]);

        //  $cartId = Cart::where('product_id', $request->id)->where('user_id', auth()->user()->id)->pluck('id')->first();
        //  $a= CustomValue::updateOrCreate(
        //     [
        //         'user_id' => auth()->user()->id,
        //         'cart_id' =>  $cartId,
        //         // 'front_neck_depth' =>  $request->front_neck_depth,
        //         // 'back_neck_depth' =>  $request->back_neck_depth,
        //         // 'waist_round' =>  $request->waist_round,
        //         // 'bust' =>  $request->bust,
        //         // 'hip' =>  $request->hip,
        //         // 'upper_bust' =>  $request->upper_bust,
        //         // 'waist' =>  $request->waist,
        //         // 'lower_waist' =>  $request->lower_waist,
        //         // 'thigh_circumference' =>  $request->thigh_circumference,
        //         // 'calf_circumference' =>  $request->calf_circumference,
        //         // 'ank  le_circumference' =>  $request->ankle_circumference,
        //         // 'arm_hole' =>  $request->arm_hole,
        //         // 'sleeve_length' =>  $request->sleeve_length,
        //         // 'sleeve_circumference' =>  $request->sleeve_circumference,
        //         // 'top_length' =>  $request->top_length,
        //         // 'bottom_length' =>  $request->bottom_length,
        //         // 'shoulder' =>  $request->shoulder,
        //         // 'order_notes' =>  $request->order_notes,

        //     ],

            
        //     // $customData
        // );


        // 'order_notes','measurement'

        $request->validate([
            'order_notes' => 'nullable|string|max:200',
            'measurement' => 'required|string',
        ]);


        if ($custom) { 
            $customData = [];
            $validator = Validator::make($request->all(), $rules);


             $cartId = Cart::where('product_id', $request->id)->where('user_id', auth()->user()->id)->pluck('id')->first();

             $a= CustomValue::updateOrCreate(
            [
                'user_id' => auth()->user()->id,
                'cart_id' =>  $cartId,
                'front_neck_depth' =>  $request->front_neck_depth,
                'back_neck_depth' =>  $request->back_neck_depth,
                'upper_bust' =>  $request->upper_bust,
                'bust' =>  $request->bust,
                'waist' =>  $request->waist,
                'waist_round' =>  $request->waist_round,
                'lower_waist' =>  $request->lower_waist,
                'hip' =>  $request->hip,
                'thigh_circumference' =>  $request->thigh_circumference,
                'knee_circumference' =>  $request->knee_circumference,
                'calf_circumference' =>  $request->calf_circumference,
                'ankle_circumference' =>  $request->ankle_circumference,
                'arm_hole' =>  $request->arm_hole,
                'sleeve_length' =>  $request->sleeve_length,
                'sleeve_circumference' =>  $request->sleeve_circumference,
                'top_length' =>  $request->top_length,
                'bottom_length' =>  $request->bottom_length,
                'shoulder' =>  $request->shoulder,
                'order_notes' =>  $request->order_notes,
                'measurement' =>  $request->measurement,

            ],

            
            // $customData
        );

           
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            foreach ($custom_values as $value) {
            //   if (empty($request->$value)) {
            //     return response()->json(['errors' => [$value =>'This field is required']], 400);
            //   }
              if (!is_numeric($request->$value) || intval($request->$value) != $request->$value) {
                return response()->json(['errors' => [$value => 'Value should be an integer']], 400);
               }
             $customData[$value] = (int)$request->$value;
            }
        }



    
    

     

        return response()->json([
            'result' => true,
            'message' => translate('Product added to cart successfully')
        ]);
       
    }

    public function changeQuantity(Request $request)
    {
        $cart = Cart::find($request->id);
        if ($cart != null) {

            if ($cart->product->stocks->where('variant', $cart->variation)->first()->qty >= $request->quantity) {
                $cart->update([
                    'quantity' => $request->quantity
                ]);

                return response()->json(['result' => true, 'message' => translate('Cart updated')], 200);
            } else {
                return response()->json(['result' => false, 'message' => translate('Maximum available quantity reached')], 400);
            }
        }

        return response()->json(['result' => false, 'message' => translate('Something went wrong')], 404);
    }

    public function process(Request $request)
    {
        $cart_ids = explode(",", $request->cart_ids);
        $cart_quantities = explode(",", $request->cart_quantities);

        if (!empty($cart_ids)) {
            $i = 0;
            foreach ($cart_ids as $cart_id) {
                $cart_item = Cart::where('id', $cart_id)->first();
                $product = Product::where('id', $cart_item->product_id)->first();

                if ($product->min_qty > $cart_quantities[$i]) {
                    return response()->json(['result' => false, 'message' => translate("Minimum")." {$product->min_qty} ".translate("item(s) should be ordered for")." {$product->name}"], 400);
                }

                $stock = $cart_item->product->stocks->where('variant', $cart_item->variation)->first()->qty;
                $variant_string = $cart_item->variation != null && $cart_item->variation != "" ? " ($cart_item->variation)" : "";
                if ($stock >= $cart_quantities[$i] || $product->digital == 1) {
                    $cart_item->update([
                        'quantity' => $cart_quantities[$i]
                    ]);

                } else {
                    if ($stock == 0 ) {
                        return response()->json(['result' => false, 'message' => translate("No item is available for")." {$product->name}{$variant_string},".translate("remove this from cart")], 404);
                    } else {
                        return response()->json(['result' => false, 'message' => translate("Only")." {$stock} ".translate("item(s) are available for")." {$product->name}{$variant_string}"], 400);
                    }

                }

                $i++;
            }

            return response()->json(['result' => true, 'message' => translate('Cart updated')], 200);

        } else {
            return response()->json(['result' => false, 'message' => translate('Cart is empty')], 404);
        }


    }

    public function destroy($id)
    {
        Cart::destroy($id);
        return response()->json(['result' => true, 'message' => translate('Product is successfully removed from your cart')], 200);
    }
}
