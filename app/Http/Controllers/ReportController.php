<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CommissionHistory;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Search;
use App\Models\Shop;
use Auth;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InHouseSaleReportExport;
use App\Models\StockReportExport;
use App\Models\WishListReportExport;
use App\Models\SalesReportExport;
use App\Models\ReturnReportExport;
use App\Models\ProductTypeReportExport;
use App\Models\Order;
use App\Models\OrderDetail;
use Session;

class ReportController extends Controller
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

    public function stock_report(Request $request)
    {
        $sort_by =null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products','sort_by'));
    }

    public function in_house_sale_report(Request $request)
    {

        $sort_by =null;
        $products = Product::orderBy('num_of_sale', 'desc')->where('added_by', 'admin');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.in_house_sale_report', compact('products','sort_by'));
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by =null;
        // $sellers = User::where('user_type', 'seller')->orderBy('created_at', 'desc');
        $sellers = Shop::with('user')->orderBy('created_at', 'desc');
        if ($request->has('verification_status')){
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers','sort_by'));
    }

    public function wish_report(Request $request)
    {
        $sort_by =null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(10);
        return view('backend.reports.wish_report', compact('products','sort_by'));
    }

    public function user_search_report(Request $request){
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }
    
    public function commission_history(Request $request) {
        $seller_id = null;
        $date_range = null;
        
        if(Auth::user()->user_type == 'seller') {
            $seller_id = Auth::user()->id;
        } if($request->seller_id) {
            $seller_id = $request->seller_id;
        }
        
        $commission_history = CommissionHistory::orderBy('created_at', 'desc');
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id){
            
            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }
        
        $commission_history = $commission_history->paginate(10);
        if(Auth::user()->user_type == 'seller') {
            return view('seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }
    
    public function wallet_transaction_history(Request $request) {
        $user_id = null;
        $date_range = null;
        
        if($request->user_id) {
            $user_id = $request->user_id;
        }
        
        $users_with_wallet = User::whereIn('id', function($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($user_id){
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }
        
        $wallets = $wallet_history->paginate(10);

        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }

    public function in_house_sale_report_pdf_download(){
        $products = Product::all();
         return PDF::loadView('backend.reports.downloads.in_house_sale_report_pdf',[
            'products' => $products,
        ], [], [])->download('in_house_sale_report_pdf.pdf');  
    }

    public function in_house_sale_report_excel(){
         return Excel::download(new InHouseSaleReportExport, 'in_house_excel_report.xlsx');
    }

    public function stock_report_pdf(){
        $products = Product::all();
        return PDF::loadView('backend.reports.downloads.stock_report_pdf',[
            'products' => $products,
        ], [], [])->download('stock_report_pdf.pdf');
    }

    public function stock_report_excel(){
        return Excel::download(new StockReportExport, 'stock_excel_report.xlsx');
    }

    public function wishlist_report_pdf(){
        $products = Product::all();
         return PDF::loadView('backend.reports.downloads.wishlist_report_pdf',[
            'products' => $products,
        ], [], [])->download('wishlist_report_pdf.pdf');
    }

    public function wishlist_report_excel(){
       return Excel::download(new WishListReportExport, 'wishlist_excel_report.xlsx');
    }

    public function sales_report(Request $request){
        $orders = Order::with('user')->get();
        $start_date = null;
        $end_date = null;
        $sort_by = null;

        
        
        if ($request->start_date) {
           $orders =  $orders->where('created_at','>=', $request->start_date);
           $start_date = $request->start_date;
        }
        if ($request->end_date) {
             $orders =  $orders->where('created_at','<=', $request->end_date);
            $end_date = $request->end_date;
        }
        return view('backend.reports.sales_report', compact('orders','start_date','end_date','sort_by'));
    }

    public function product_type_report(Request $request){
        $products = Product::all();
        $sort_by =null;
        $product_type = null;
        $start_date = null;
        $end_date = null;
       
        if ($request->has('product_type')){
            $sort_by = $request->product_type;
            $products = $products->where('product_type', $sort_by);
        }
        if ($request->product_type != null) {
            $products = $products->where('product_type', $request->product_type);
            $product_type = $request->product_type;
        }
        if ($request->start_date) {
           $products = $products->where('created_at','>=', $request->start_date);
           $start_date = $request->start_date;
        }
        if ($request->end_date) {
            $products = $products->where('created_at','<=', $request->end_date);
            $end_date = $request->end_date;
        }
        return view('backend.reports.product_type_report',compact('products','sort_by','product_type', 'start_date','end_date'));
    }

    public function sales_report_pdf(){
        $orders = Order::all();
         return PDF::loadView('backend.reports.downloads.sales_report_pdf',[
            'orders' => $orders
        ], [], [])->download('sales_report_pdf.pdf');
    }

    public function sales_report_excel(){
          return Excel::download(new SalesReportExport, 'sales_excel_report.xlsx');
    }

     public function product_type_report_download(){
        $products = Product::all();
         return PDF::loadView('backend.reports.downloads.product_type_report_pdf',[
            'products' => $products
        ], [], [])->download('product_type_report_pdf.pdf');
    }

    public function product_type_report_excel(){
        return Excel::download(new ProductTypeReportExport, 'product_type_excel_report.xlsx');
    }
    


    public function return_report(Request $request){
        $order_details = OrderDetail::with('order','product')->whereIn('delivery_status', ['cancelled', 'returned'])->paginate(10);
        $start_date = null;
        $end_date = null;
        $sort_by = null;

        
        
        if ($request->start_date) {
           $order_details =  $order_details->where('updated_at','>=', $request->start_date);
           $start_date = $request->start_date;
        }
        if ($request->end_date) {
             $order_details =  $order_details->where('updated_at','<=', $request->end_date);
            $end_date = $request->end_date;
        }

    
    return view('backend.reports.return_report',compact('order_details','start_date','end_date','sort_by'));

    }


    public function return_report_pdf(){
        $orders = OrderDetail::with('order','product')->whereIn('delivery_status', ['cancelled', 'returned'])->get();
         return PDF::loadView('backend.reports.downloads.return_report_pdf',[
            'orders' => $orders
        ], [], [])->download('return_report_pdf.pdf');
    }


    public function return_report_excel(){
        return Excel::download(new ReturnReportExport, 'return_excel_report.xlsx');
  }

}
