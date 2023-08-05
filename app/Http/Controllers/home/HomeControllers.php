<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\analysis\Analysis;
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
        $category = Category::whereNull('deleted_at')->get();
        return view('home.index', [
            "title" => "Best Seller",
            "category" => $category
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
