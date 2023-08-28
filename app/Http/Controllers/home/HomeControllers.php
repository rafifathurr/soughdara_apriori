<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\analysis\Analysis;
use App\Models\analysis\Recommend;
use App\Models\analysis\Kombinasi;
use App\Models\product\Product;
use App\Models\category\Category;

class HomeControllers extends Controller
{

    // Index View and Scope Data
    public function home()
    {
        date_default_timezone_set("Asia/Bangkok");
        $selected_product = [];
        $category = Category::whereNull('deleted_at')->get();

        $analysis = Recommend::orderBy('analysis_date', 'desc')
                    ->first();

        if(!is_null($analysis)){

            $kd_analysis = $analysis->kd_analysis;

            $confidence_check = Recommend::where('kd_analysis', $kd_analysis)->orderBy('id', 'asc')->get();

            return view('home.index', [
                "title" => "Recommendation",
                "category" => $category,
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
