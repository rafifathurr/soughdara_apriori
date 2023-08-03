<?php

namespace App\Http\Controllers\analysis;

use App\Http\Controllers\Controller;
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

class AnalysisControllers extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index View and Scope Data
    public function index()
    {

        date_default_timezone_set("Asia/Bangkok");
        $years = Orders::selectRaw('YEAR(date) as year')
                ->whereNull('deleted_at')
                ->groupBy('year')
                ->get();

        return view('analysis.index', [
            "title" => "Analysis Process",
            "years" => $years
        ]);
    }

    public function create($month, $year)
    {

        $data = Details::get();
        $max_new = 0;
        $max_old = 0;

        foreach($data as $item){
            $count = Details::where('id_order', $item->id_order)->groupBy('id_order')->count();
            $max_old = $count;

            if($max_new < $max_old){
                $max_new = $max_old;
            }
        }
        
        dd($max_new);

        $data['title'] = "Add Analysis Process";
        $data['url'] = 'store';
        $data['disabled_'] = '';
        $data['tahun'] = $year;
        $data['bulan'] = $month;
        return view('analysis.create', $data);
    }

}
