<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Http;
// use GuzzleHttp\Client;

class APIController extends Controller
{
    public function PushOrder($id){
        $getResult = Order::pushOrder($id);
        // return $getResult;
        return response()->json(['status'=>$getResult['status'],'message'=>$getResult['message']]);  
    }




}
