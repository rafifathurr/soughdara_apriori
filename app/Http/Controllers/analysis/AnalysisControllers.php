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

        $check = Details::select('details_order.id_order')
                ->join('orders_new', 'orders_new.id', '=', 'details_order.id_order')
                ->whereMonth('orders_new.date', $month)
                ->whereYear('orders_new.date', $year)
                ->groupBy('details_order.id_order')
                ->get();

        $max_new = 0;
        $max_old = 0;

        foreach($check as $item){
            $count = Details::where('details_order.id_order', $item->id_order)
                    ->join('orders_new', 'orders_new.id', '=', 'details_order.id_order')
                    ->groupBy('details_order.id_order')
                    ->count();

            $max_old = $count;

            if($max_new < $max_old){
                $max_new = $max_old;
            }
        }

        $data['first_data'] = Details::with('product')
                            ->selectRaw('
                                details_order.id_product,
                                count(*) as total
                            ')
                            ->join('orders_new', 'orders_new.id', '=', 'details_order.id_order')
                            ->whereMonth('orders_new.date', $month)
                            ->whereYear('orders_new.date', $year)
                            ->groupBy('details_order.id_product')
                            ->get();
        $data['max_product'] = 1;
        $data['title'] = "Add Analysis Process";
        $data['url'] = 'store';
        $data['disabled_'] = '';
        $data['tahun'] = $year;
        $data['bulan'] = $month;
        return view('analysis.create', $data);
    }

}