<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{


    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
 
    public function refund_requests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id', 'seller_id');
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function club_point()
    {
        return $this->hasMany(ClubPoint::class);
    }

    public function delivery_boy()
    {
        return $this->belongsTo(User::class, 'assign_delivery_boy', 'id');
    }

    

    public function proxy_cart_reference_id()
    {
        return $this->hasMany(ProxyPayment::class)->select('reference_id');
    }



    public function order_items()
    {
        return $this->hasMany('App\Models\OrderDetail','order_id');
    }







    



// public static function createReturnOrder($order_id){
//     // Construct the return order data
//     $orderDetails =Order::with('order_items','user')->where('id',$order_id)->first()->toArray();


//         $orderDetails['order_id']=$orderDetails['code'];
//         $orderDetails['order_date']=$orderDetails['created_at'];
//         $orderDetails['pickup_location']="Primary";
//         $orderDetails['channel_id']="4294547";
//         $orderDetails['comment']="Test Order";
//         $orderDetails['billing_customer_name']=$orderDetails['user']['name'];
//         $orderDetails['billing_last_name']="";
//         // $orderDetails['billing_address']=$orderDetails['user']['address'];
//         $orderDetails['billing_address']="manjeri";

//         // $orderDetails['billing_address_2']=$orderDetails['additional_info'];
//         $orderDetails['billing_address_2']="manjeri";
//         $orderDetails['billing_city']=$orderDetails['user']['city'];
//         // $orderDetails['billing_pincode']=$orderDetails['user']['postal_code'];
//         $orderDetails['billing_pincode']="676122";
//         // $orderDetails['billing_state']=$orderDetails['user']['state'];
//         $orderDetails['billing_state']="kerala";
//         // $orderDetails['billing_country']=$orderDetails['user']['country'];
//         $orderDetails['billing_country']="india";
//         $orderDetails['billing_email']=$orderDetails['user']['email'];
//         // $orderDetails['billing_phone']=$orderDetails['user']['phone'];
//         $orderDetails['billing_phone']="9744565755";
//         $orderDetails['shipping_is_billing']=true;
//         $orderDetails['shipping_customer_name']="bbb";
//         $orderDetails['shipping_last_name']="aaa ";
//         $orderDetails['shipping_address']="manjeri";
//         $orderDetails['shipping_address_2']="nellikuth";
//         $orderDetails['shipping_city']="nellikuth ";
//         $orderDetails['shipping_pincode']="get shiping addrss array ";
//         $orderDetails['shipping_country']="india";
//         $orderDetails['shipping_state']="get shiping addrss array ";
//         $orderDetails['shipping_email']="test@gmail.com";
//         $orderDetails['shipping_phone']="8765435456";

//         foreach ($orderDetails['order_items'] as $key => $item) {
//             $orderDetails['order_items'][$key]['name']=$item['product_id'];
//             $orderDetails['order_items'][$key]['sku']=$item['product_id'];
//             $orderDetails['order_items'][$key]['units']=$item['quantity'];
//             $orderDetails['order_items'][$key]['selling_price']=$item['price'];
//             $orderDetails['order_items'][$key]['discount']="";
//             $orderDetails['order_items'][$key]['tax']=$item['tax'];
//             $orderDetails['order_items'][$key]['hsn']="";
//         }

//         $orderDetails['payment_method']="prepaid";
//         $orderDetails['shipping_charges']=0;
//         $orderDetails['giftwrap_charges']=0;
//         $orderDetails['transaction_charges']=0;
//         $orderDetails['total_discount']=0;
//         $orderDetails['sub_total']=$orderDetails['grand_total'];
//         $orderDetails['length']=1;
//         $orderDetails['breadth']=1;
//         $orderDetails['height']=1;
//         $orderDetails['weight']=1;


//     $returnOrderData = json_encode($returnOrderData);

//     // Generate an access token for Shiprocket (similar to your existing code)

//         $c = curl_init();
//         $url = "https://apiv2.shiprocket.in/v1/external/auth/login";
//         curl_setopt($c, CURLOPT_URL, $url);

//         curl_setopt($c, CURLOPT_POST, 1);
//         curl_setopt($c, CURLOPT_POSTFIELDS, "email=abcdabcdabcd@gmail.com&password=123456789");
//         curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
//         $server_output  = curl_exec($c);
//         curl_close($c);
//         $server_output=json_decode($server_output,true);


//     // Make an API request to Shiprocket to create the return order
//     $c = curl_init();
//     $url = "https://apiv2.shiprocket.in/v1/external/returns/create";
//     curl_setopt($c, CURLOPT_URL, $url);
//     curl_setopt($c, CURLOPT_POST, 1);
//     curl_setopt($c, CURLOPT_POSTFIELDS, $returnOrderData);
//     // Set necessary headers, including the Shiprocket access token (similar to your existing code)
//     curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
//     curl_setopt($c,CURLOPT_HTTPHEADER,array('Content-Type:application/json','Authorization:Bearer'
//             .$server_output['token'].''));

//     $result = curl_exec($c);
//     curl_close($c);

//     $result = json_decode($result, true);

//     if(isset($result['status_code']) && $result['status_code'] == 1){
//         // Update your database or handle any necessary logic for a successful return order creation
//         $status = "true";
//         $message = "Return order successfully created in Shiprocket";
//     } else {
//         // Handle the case where return order creation failed
//         $status = "false";
//         $message = "Failed to create the return order in Shiprocket";
//     }

//     return ['status' => $status, 'message' => $message];
// }









    public static function pushOrder($order_id){
        $orderDetails =Order::with('order_items','user')->where('id',$order_id)->first()->toArray();


        $orderDetails['order_id']=$orderDetails['code'];
        $orderDetails['order_date']=$orderDetails['created_at'];
        $orderDetails['pickup_location']="Primary";
        $orderDetails['channel_id']="4294547";
        $orderDetails['comment']="Test Order";
        $orderDetails['billing_customer_name']=$orderDetails['user']['name'];
        $orderDetails['billing_last_name']="";
        // $orderDetails['billing_address']=$orderDetails['user']['address'];
        $orderDetails['billing_address']="manjeri";

        // $orderDetails['billing_address_2']=$orderDetails['additional_info'];
        $orderDetails['billing_address_2']="manjeri";
        $orderDetails['billing_city']=$orderDetails['user']['city'];
        // $orderDetails['billing_pincode']=$orderDetails['user']['postal_code'];
        $orderDetails['billing_pincode']="676122";
        // $orderDetails['billing_state']=$orderDetails['user']['state'];
        $orderDetails['billing_state']="kerala";
        // $orderDetails['billing_country']=$orderDetails['user']['country'];
        $orderDetails['billing_country']="india";
        $orderDetails['billing_email']=$orderDetails['user']['email'];
        // $orderDetails['billing_phone']=$orderDetails['user']['phone'];
        $orderDetails['billing_phone']="9744565755";
        $orderDetails['shipping_is_billing']=true;
        $orderDetails['shipping_customer_name']="bbb";
        $orderDetails['shipping_last_name']="aaa ";
        $orderDetails['shipping_address']="manjeri";
        $orderDetails['shipping_address_2']="nellikuth";
        $orderDetails['shipping_city']="nellikuth ";
        $orderDetails['shipping_pincode']="get shiping addrss array ";
        $orderDetails['shipping_country']="india";
        $orderDetails['shipping_state']="get shiping addrss array ";
        $orderDetails['shipping_email']="test@gmail.com";
        $orderDetails['shipping_phone']="8765435456";

        foreach ($orderDetails['order_items'] as $key => $item) {
            $orderDetails['order_items'][$key]['name']=$item['product_id'];
            $orderDetails['order_items'][$key]['sku']=$item['product_id'];
            $orderDetails['order_items'][$key]['units']=$item['quantity'];
            $orderDetails['order_items'][$key]['selling_price']=$item['price'];
            $orderDetails['order_items'][$key]['discount']="";
            $orderDetails['order_items'][$key]['tax']=$item['tax'];
            $orderDetails['order_items'][$key]['hsn']="";
        }

        $orderDetails['payment_method']="prepaid";
        $orderDetails['shipping_charges']=0;
        $orderDetails['giftwrap_charges']=0;
        $orderDetails['transaction_charges']=0;
        $orderDetails['total_discount']=0;
        $orderDetails['sub_total']=$orderDetails['grand_total'];
        $orderDetails['length']=1;
        $orderDetails['breadth']=1;
        $orderDetails['height']=1;
        $orderDetails['weight']=1;


        // echo "<pre>"; print_r(json_encode($orderDetails));die;

        $orderDetails=json_encode($orderDetails);

        //generate access token

        $c = curl_init();
        $url = "https://apiv2.shiprocket.in/v1/external/auth/login";
        curl_setopt($c, CURLOPT_URL, $url);

        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, "email=abcdabcdabcd@gmail.com&password=123456789");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $server_output  = curl_exec($c);
        curl_close($c);
        $server_output=json_decode($server_output,true);
        // echo "<pre>";print_r($server_output);die;    

        //create order in ship rocket
        $url="https://apiv2.shiprocket.in/v1/external/orders/create/adhoc";
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS,$orderDetails );
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c,CURLOPT_HTTPHEADER,array('Content-Type:application/json','Authorization:Bearer'
            .$server_output['token'].''));

        $result = curl_exec($c);
        curl_close($c);

        $result = json_decode($result,true);


        if (isset($result['status_code']) && $result['status_code'] == 1) {
            // Update the order table column 'is_pushed' to 1
            Order::where('id', $order_id)->update(['is_pushed' => 1,'tracking_code'=>$result['order_id']]);
            $status = "true";
            $message = "Order successfully pushed in Ship Rocket";
            $shipmentId = $result['order_id'];
        } else {
            // Add code for the else condition here
            $status = "false";
            $message = "Failed to push the order to Ship Rocket ,Please contact Admin" ;
            $shipmentId = null;
        }
        
        // return $array(['status'=>$status,'message'=>$message]);
        return ['status' => $status, 'message' => $message ,'shipment_id' => $shipmentId];

        return $orderDetails;
      
    }


    
}
