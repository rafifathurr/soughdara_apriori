<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\analysis\Analysis;
use App\Models\analysis\Support;
use App\Models\analysis\Kombinasi;
use App\Models\analysis\DetailsAnalysis;
use App\Models\order\Orders;
use App\Models\order\detail\Details;
use App\Models\product\Product;
use App\Models\category\Category;
use App\Models\payment_method\PaymentMethod;
use App\Exports\ReportOrderExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Auth;
use Session;
use DB;

class HomeControllers extends Controller
{

    // Index View and Scope Data
    public function home()
    {
        date_default_timezone_set("Asia/Bangkok");
        $selected_product = [];
        $category = Category::whereNull('deleted_at')->get();

        $analysis = Analysis::orderBy('month', 'desc')->orderBy('year', 'desc')->orderBy('id', 'desc')->first();
        $min_support = $analysis->min_support;
        $min_confidence = $analysis->min_confidence;

        $support_check = Support::where('support', '>=', $min_support)->orderBy('support', 'desc')->get();
        $confidence_check = Support::where('support', '>=', $min_confidence)->orderBy('support', 'desc')->get();

        foreach($support_check as $check){
            array_push($selected_product, $check->id_product);
        }

        foreach($confidence_check as $check){
            array_push($selected_product, $check->id_product_a);
            array_push($selected_product, $check->id_product_b);
        }

        $menus = Product::whereIn('id', $selected_product)->whereNull('deleted_at')->get();

        return view('home.index', [
            "title" => "Best Seller",
            "category" => $category,
            "menus" => $menus
        ]);
    }

    public function category($id)
    {
        date_default_timezone_set("Asia/Bangkok");
        $category_data = Category::where('id', $id)->whereNull('deleted_at')->first();
        $menus = Product::where('category_id', $id)->whereNull('deleted_at')->get();
        $category = Category::whereNull('deleted_at')->get();
        return view('home.index', [
            "title" => "".$category_data->category."",
            "category" => $category,
            "menus" => $menus
        ]);
    }

}
