<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CommissionHistory;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Banner;
use App\Models\Shop;
Use App\Models\Category;
use Auth;
use DB;

class  BannerController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:in_house_product_sale_report'])->only('in_house_sale_report');
        $this->middleware(['permission:seller_products_sale_report'])->only('seller_sale_report');
        $this->middleware(['permission:products_stock_report'])->only('stock_report');
        $this->middleware(['permission:product_wishlist_report'])->only('wish_report');
        $this->middleware(['permission:user_search_report'])->only('user_search_report');
        $this->middleware(['permission:commission_history_report'])->only('commission_history');
        $this->middleware(['permission:wallet_transaction_report'])->only('wallet_transaction_history');
    }
    public function home_slide(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }
        $categories = Category::select('categories.*')
         ->leftJoin('banners', 'categories.id', '=', 'banners.category_id')
         ->get();
        return view('backend.banner.home_slide', compact('colors_active','categories'));
    }

    public function banners(Request $request)
    {
        return view('backend.banner.banners');
    }
     
    public function banners_edit(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $categories = Category::all();
        return view('backend.banner.banners_edit', compact('banner','categories'));
    }

    public function home_section2_edit(Request $request, $id){

        $banner = Banner::findOrFail($id);
        $categories = Category::all();
        return view('backend.banner.home_section2_edit', compact('banner','categories'));
    }

    public function store(Request $request)
    {
        // Retrieve the values from the request
        $urls = $request->home_slider_links;
        //btn name to texts
        $texts = $request->text;
        // $subtexts = $request->subtext;
        // $colours = $request->colour;
        $images = $request->image;
        $mob_images  = $request->mobile_image;
        $orders = $request->order;
        $category = $request->category_id;
      

        foreach ($urls as $key => $url) {
            $banner = new Banner();
            $banner->url = $url;
            $banner->text = $texts;
            // $banner->subtext = $subtexts[$key];
            // $banner->colour = $colours[$key];
            $banner->order = $orders[$key];
            $banner->image = $images[$key];
            $banner->mobile_image = $mob_images[$key];
            $banner->category_id = $category[$key];
            $banner->section = 'section1';
            $banner->save();
        }
    
        flash(translate("Settings updated successfully"))->success();
        return back();
    }
     public function delete($id)
    {
        try {
            Banner::destroy($id);
            flash(translate('Product has been deleted successfully'))->success();
            return back();

        } catch (\Exception $e) {
            flash(translate('Something went wrong'))->error();
            return back();        }

    }
    public function home_section2()
    {
         $categories = Category::select('categories.*')
         ->leftJoin('banners', 'categories.id', '=', 'banners.category_id')
         ->get();
        return view('backend.banner.home_section2',compact('categories'));
    }

    public function home_section3()
    {
        $categories = Category::select('categories.*')
         ->leftJoin('banners', 'categories.id', '=', 'banners.category_id')
         ->get();
        return view('backend.banner.home_section3',compact('categories'));
    }

    public function home_section4()
    {
          $categories = Category::select('categories.*')
         ->leftJoin('banners', 'categories.id', '=', 'banners.category_id')
         ->get();
        return view('backend.banner.home_section4',compact('categories'));
    }
    
   public function update(Request $request, $id)
   {
    $banner = Banner::findOrFail($id);
    $banner->image = $request->image;
    $banner->text = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->text));
    $banner->order = $request->order;
    $banner->url = $request->url;
    $banner->mobile_image = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-',$request->mobile_image));
    $banner->category_id = $request->category_id;
    $banner->save();

    flash(translate('Banner has been updated successfully'))->success();
        return back();
   }

   public function store_home3(Request $request)
   {
        $urls = $request->home_slider_links;
        //txts to btn name
        $texts = $request->text;
        $images = $request->image;
        $orders = $request->order;
        $mob_images  = $request->mobile_image;
        $category = $request->category_id;
        foreach ($urls as $key => $url) {
            $banner = new Banner();
            $banner->url = $url;
            $banner->text = $texts[$key];
            $banner->order = $orders[$key];
            $banner->image = $images[$key];
            $banner->mobile_image = $mob_images[$key];
            $banner->category_id = $category[$key];
            $banner->section = 'section3';
            $banner->save();
        }
          flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function store_home4(Request $request)
    {
        $urls = $request->home_slider_links;
        //btn name to texts
        $texts = $request->text;
       
        $images = $request->image;
        $mob_images  = $request->mobile_image;
        $orders = $request->order;
        $category = $request->category_id;
        foreach ($urls as $key => $url) {
            $banner = new Banner();
            $banner->url = $url;
            $banner->text = $texts[$key];
            $banner->order = $orders[$key];
            $banner->image = $images[$key];
            $banner->mobile_image = $mob_images[$key];
            $banner->category_id = $category[$key];
            $banner->section = 'section4';
            $banner->save();
        }
          flash(translate("Settings updated successfully"))->success();
        return back();
    }
    
    public function store_home2(Request $request)
    {
         $urls = $request->home_slider_links;
         $images = $request->image;
         $columns = $request->column;
         $mob_images  = $request->mobile_image;
         $category = $request->category_id;
              
           foreach ($urls as $key => $url) {
            $banner = new Banner();
            $banner->url = $url;
            $banner->image = $images[$key];
            $banner->column = $columns[$key];
            $banner->mobile_image = $mob_images[$key];
            $banner->category_id = $category[$key];
            $banner->section = 'section2';
            $banner->save();
        }
          flash(translate("homesection2 stored successfully"))->success();
        return back();

    }

    public function update_home2(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->image = $request->image;
        $banner->url = $request->url;
        $banner->column = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->column));
        $banner->mobile_image = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-',$request->mobile_image));
        $banner->category_id = $request->category_id;
        $banner->save();

         flash(translate("home section2 updated successfully"))->success();
        return back();
    }
}
