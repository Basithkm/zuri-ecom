<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Currency;
use App\Models\Language;
use App\Models\Order;
use Session;
use PDF;
use Config;
use Storage;

class InvoiceController extends Controller
{
    //download invoice
    public function invoice_download($id)
    {
        if (Session::has('currency_code')) {
            $currency_code = Session::get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = Session::get('locale', Config::get('app.locale'));

        if (Language::where('code', $language_code)->first()->rtl == 1) {
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        } else {
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
        }

        if (
            $currency_code == 'BDT' ||
            $language_code == 'bd'
        ) {
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        } elseif (
            $currency_code == 'KHR' ||
            $language_code == 'kh'
        ) {
            // khmer font
            $font_family = "'Hanuman','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
            // }elseif($currency_code == 'ILS'){
            //     // Israeli font
            //     $font_family = "'Varela Round','sans-serif'";
        } elseif (
            $currency_code == 'AED' ||
            $currency_code == 'EGP' ||
            $language_code == 'sa' ||
            $currency_code == 'IQD' ||
            $language_code == 'ir' ||
            $language_code == 'om' ||
            $currency_code == 'ROM' ||
            $currency_code == 'SDG' ||
            $currency_code == 'ILS' ||
            $language_code == 'jo'
        ) {
            // middle east/arabic/Israeli font
            $font_family = "'Baloo Bhaijaan 2','sans-serif'";
        } elseif ($currency_code == 'THB') {
            // thai font
            $font_family = "'Kanit','sans-serif'";
        } elseif (
            $currency_code == 'CNY' ||
            $language_code == 'zh'
        ) {
            // Chinese font
            $font_family = "'yahei','sans-serif'";
        } elseif (
            $currency_code == 'kyat' ||
            $language_code == 'mm'
        ) {
            // Myanmar font
            $font_family = "'pyidaungsu','sans-serif'";
        } elseif (
            $currency_code == 'THB' ||
            $language_code == 'th'
        ) {
            // Thai font
            $font_family = "'zawgyi-one','sans-serif'";
        } else {
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }

        // $config = ['instanceConfigurator' => function($mpdf) {
        //     $mpdf->showImageErrors = true;
        // }];
        // mpdf config will be used in 4th params of loadview

        $config = [];

        $order = Order::findOrFail($id);
        // print_r($order->shipping_address);die;
        $pdf =  PDF::loadView('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ], [], $config);
        // ->download('order-' . $order->code . '.pdf');
        $pdfContent = $pdf->output();

        $pdfPath = 'invoice/order-' . $order->code . '.pdf';
        $publicPath = public_path($pdfPath); // Get the absolute path to the public/pdf folder
        
        // Check if the directory exists, if not, create it
        if (!is_dir(dirname($publicPath))) {
            mkdir(dirname($publicPath), 0755, true);
        }
        file_put_contents($publicPath, $pdfContent); // Save the PDF in the public/pdf folder
        $publicUrl = static_asset($pdfPath); // Get the public URL of the saved PDF
        return response()->json([
            'result' => true,
            'pdf' => $publicUrl
        ]);
    }
}
