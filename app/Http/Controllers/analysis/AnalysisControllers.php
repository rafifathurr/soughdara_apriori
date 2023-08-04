<?php

namespace App\Http\Controllers\analysis;

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
        $analysis = Analysis::whereNull('deleted_at')->get();

        return view('analysis.index', [
            "title" => "Analysis Process",
            "years" => $years,
            "analysis" => $analysis
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

        for($i = 1; $i <= $max_new; $i++){
            if($i == 1){
                $data['itemset'][$i][] = Details::with('product')
                        ->selectRaw('
                            details_order.id_product,
                            product.product_name,
                            count(*) as total
                        ')
                        ->join('orders_new', 'orders_new.id', '=', 'details_order.id_order')
                        ->join('product', 'product.id', '=', 'details_order.id_product')
                        ->whereMonth('orders_new.date', $month)
                        ->whereYear('orders_new.date', $year)
                        ->where('event_type', 'Payment')
                        ->groupBy('details_order.id_product')
                        ->groupBy('product.product_name')
                        ->orderBy('total','desc')
                        ->get();
            }else{
                $data['itemset'][$i][] = DB::select("
                    SELECT
                        combined_products as product_name,
                        product_id as id_product,
                        COUNT(*) AS total
                    FROM (
                        SELECT
                            o.id AS order_id,
                            GROUP_CONCAT(p.product_name ORDER BY p.product_name ASC) AS combined_products,
                            GROUP_CONCAT(d.id_product ORDER BY p.product_name ASC) AS product_id
                        FROM
                            orders_new o
                        JOIN
                            details_order d ON o.id = d.id_order
                        JOIN
                            product p ON d.id_product = p.id
                        WHERE
			                MONTH(o.date) = ".$month." and YEAR(o.date) = ".$year." and o.event_type = 'Payment'
                        GROUP BY
                            o.id
                        HAVING
                            COUNT(DISTINCT d.id_product) = ".$i."
                    ) AS combined_orders
                    GROUP BY
                        combined_products, product_id
                    ORDER BY
                        total DESC
                ");
            }
        }

        $data['total_transaksi'] = Orders::whereMonth('orders_new.date', $month)
                                ->whereYear('orders_new.date', $year)
                                ->count();

        $data['max_product'] = $max_new;
        $data['title'] = "Add Analysis Process";
        $data['url'] = 'store';
        $data['disabled_'] = '';
        $data['tahun'] = $year;
        $data['bulan'] = $month;
        return view('analysis.create', $data);
    }

    public function store(Request $req){
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');

        $analysis_first = Analysis::create([
            'month' => $req->month,
            'year' => $req->year,
            'total_transaction' => $req->total_order,
            'min_support' => $req->min_support,
            'created_at' => $datenow
        ]);

        if($analysis_first){

            $products = $req->id_product;

            foreach($products as $key=>$prods){

                $details = DetailsAnalysis::create([
                    'id_analysis' => $analysis_first->id,
                    'product' => $prods,
                    'total_all' => $req->total_per_product[$key],
                    'support_value' => $req->support[$key],
                    'status' => $req->result[$key],
                    'created_at' => $datenow
                ]);

            }

        }

        return redirect()->route('admin.analysis.index')->with(['success' => 'Data successfully stored!']);
    }

}
