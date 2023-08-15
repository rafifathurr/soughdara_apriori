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

        if(!is_null($analysis)){

            $kd_analysis = $analysis->kd_analysis;
            $min_support = $analysis->min_support;
            $min_confidence = $analysis->min_confidence;

            $confidence_check = Kombinasi::where('kd_analysis', $kd_analysis)->where('support', '>=', $min_support)->where('confidence', '>=', $min_confidence)->orderBy('support', 'desc')->orderBy('confidence', 'desc')->get();

            $menus = Product::whereIn('id', $selected_product)->whereNull('deleted_at')->get();

            return view('home.index', [
                "title" => "Recommendation",
                "category" => $category,
                "menus" => $menus,
                "packages"=> $confidence_check
            ]);

        }else{

            return view('home.index', [
                "title" => "Recommendation",
                "category" => $category
            ]);

        }
    }

    public function category($id)
    {
        date_default_timezone_set("Asia/Bangkok");
        $category_data = Category::where('id', $id)->whereNull('deleted_at')->first();
        $menus = Product::where('category_id', $id)->whereNull('deleted_at')->get();
        $category = Category::whereNull('deleted_at')->get();
        return view('home.index', [
            "id" => $id,
            "title" => "".$category_data->category."",
            "category" => $category,
            "menus" => $menus
        ]);
    }

}
