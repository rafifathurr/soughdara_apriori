<?php

namespace App\Http\Controllers\analysis;

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
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
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

        $totalProduk = Product::whereNull('deleted_at')->count();
        $dataProduk = Product::whereNull('deleted_at')->get();

        foreach($dataProduk as $produk){
            $id_product = $produk -> id;
            $totalTransaksi = Details::where('id_product', $id_product)->whereNull('deleted_at')->count();
            $nSupport = ($totalTransaksi / $totalProduk) * 100;

            $support_process = Support::create([
                'kd_analysis' => $kd_analysis,
                'id_product' => $id_product,
                'support' => $nSupport,
                'created_at' => $datenow
            ]);
        }

        // kombinasi 2 item set
        $qProdukA = Support::where('kd_analysis', $kd_analysis) -> where('support', '>=', $support) -> get();
        foreach($qProdukA as $qProdA){
            $kdProdukA = $qProdA -> id_product;
            $qProdukB = Support::where('kd_pengujian', $kd_analysis) -> where('support', '>=', $support) -> get();
            foreach($qProdukB as $qProdB){
                $kdProdukB = $qProdB -> id_product;
                $jumB = Kombinasi::where('kd_barang_a', $kdProdukB) -> count();
                if($jumB > 0){

                }else{
                    if($kdProdukA == $kdProdukB){

                    }else{
                        $kdKombinasi = mt_rand();
                        $check = Kombinasi::where('kd_kombinasi', $kdKombinasi)->first();
                
                        if(!is_null($check)){
                            $kdKombinasi = mt_rand();
                        }

                        if($kdKombinasi == $kd_analysis){
                            $kdKombinasi = mt_rand();
                        }

                        $kombinasi_process_1 = Kombinasi::create([
                            'kd_analysis' => $kd_analysis,
                            'kd_kombinasi' => $kdKombinasi,
                            'id_product_a' => $kdProdukA,
                            'created_at' => $datenow
                        ]);

                        $nk = new Kombinasi();
                        $nk -> kd_pengujian = $kdPengujian;
                        $nk -> kd_kombinasi = $kdKombinasi;
                        $nk -> kd_barang_a = $kdProdukA;
                        $nk -> kd_barang_b = $kdProdukB;
                        $nk -> jumlah_transaksi = 0;
                        $nk -> support = 0;
                        $nk -> save();
                    }
                }
            }
        }

        // kombinasi 2 itemset phase 2
        $nilaiKombinasi = M_Nilai_Kombinasi::where('kd_pengujian', $kdPengujian) -> get();
        $no = 1;
        foreach($nilaiKombinasi as $nk){
            $kdKombinasi = $nk -> kd_kombinasi;
            $kdBarangA = $nk -> kd_barang_a;
            $kdBarangB = $nk -> kd_barang_b;

            // cari total transaksi
            $dataFaktur = M_Penjualan::distinct() -> get(['no_faktur']);
            $fnTransaksi = 0;
            foreach($dataFaktur as $faktur){
                $noFaktur = $faktur -> no_faktur;
                $qBonTransaksiA = M_Penjualan::where('no_faktur', $noFaktur) -> where('kd_barang', $kdBarangA) -> count();
                $qBonTransaksiB = M_Penjualan::where('no_faktur', $noFaktur) -> where('kd_barang', $kdBarangB) -> count();
                if($qBonTransaksiA == 1 && $qBonTransaksiB == 1){
                    $fnTransaksi++;
                }
            }
            $support = ($fnTransaksi / $totalProduk) * 100;
            M_Nilai_Kombinasi::where('kd_pengujian', $kdPengujian) -> where('kd_kombinasi', $kdKombinasi) -> update([
                'jumlah_transaksi' => $fnTransaksi,
                'support' => $support
            ]);
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
