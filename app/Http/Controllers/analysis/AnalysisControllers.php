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
    public function create($month, $year, $support, $confidence)
    {
        $data['title'] = "Analysis of ".date("F", mktime(0, 0, 0, $month, 10))." ".$year."";
        $data['min_support'] = $support;
        $data['min_confidence'] = $confidence;
        $data['tahun'] = $year;
        $data['bulan'] = $month;
        
        $kd_analysis = mt_rand();
        $check = Analysis::where('kd_analysis', $kd_analysis)->first();

        if(!is_null($check)){
            $kd_analysis = mt_rand();
        }



        return view('analysis.create', $data);
    }

    public function getMonth(Request $req){
        $analysis_get = Analysis::whereNull('deleted_at')->get();
        $month = [];

        foreach($analysis_get as $analysis){
            array_push($month, $analysis->month);
        }

        $months = Orders::selectRaw('MONTH(date) as bulan, MONTHNAME(date) as nama_bulan')
                ->whereYear('date', $req->tahun)
                ->where('deleted_at',null)
                ->whereNotIn(DB::raw("MONTH(date)"), $month)
                ->groupBy('bulan')
                ->groupBy('nama_bulan')
                ->orderBy('bulan', 'asc')
                ->get();

        return json_encode($months);
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

    public function delete(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $exec = Analysis::where('id', $req->id )->update([
            'deleted_at'=>$datenow,
            'updated_at'=>$datenow
        ]);

        if ($exec) {

            $exec_2 = DetailsAnalysis::where('id_analysis', $req->id )->update([
                'deleted_at'=>$datenow,
                'updated_at'=>$datenow
            ]);

            if ($exec_2) {

                Session::flash('success', 'Data successfully deleted!');

            } else {

                Session::flash('gagal', 'Error Data');

            }

        } else {

            Session::flash('gagal', 'Error Data');

        }
    }

}
