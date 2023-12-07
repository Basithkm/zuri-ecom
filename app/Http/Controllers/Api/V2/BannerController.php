<?php

namespace App\Http\Controllers\Api\V2;
use App\Models\Banner;
use App\Http\Resources\V2\BannerCollection;

class BannerController extends Controller
{

  public function index()
{
    $sectionOne = Banner::where('section', 'section1')->get();
    $sectionTwo = Banner::where('section', 'section2')->get();
    $sectionThree = Banner::where('section', 'section3')->get();
    $sectionFour = Banner::where('section', 'section4')->get();
    
    foreach($sectionOne as $sec =>$row) {
      $row->image_url = uploaded_asset($row->image);
      $row->mobile_image_url = uploaded_asset($row->mobile_image);
    }
  
    foreach($sectionTwo as $sec =>$row) {
      $row->image_url = uploaded_asset($row->image);
       $row->mobile_image_url = uploaded_asset($row->mobile_image);
    }

    foreach($sectionThree as $sec =>$row) {
      $row->image_url = uploaded_asset($row->image);
       $row->mobile_image_url = uploaded_asset($row->mobile_image);
    }

     foreach($sectionFour as $sec =>$row) {
      $row->image_url = uploaded_asset($row->image);
       $row->mobile_image_url = uploaded_asset($row->mobile_image);
    }

   
    $response = [
        'section1' => $sectionOne,
        'section2' => $sectionTwo,
        'section3' => $sectionThree,
        'section4' =>$sectionFour,
    ];

    return response()->json($response);
}

 
}
