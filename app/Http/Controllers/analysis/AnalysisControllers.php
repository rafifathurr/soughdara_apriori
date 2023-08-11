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
    public function create($month, $year, $min_support, $min_confidence)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $kd_analysis = mt_rand();
        $check = Analysis::where('kd_analysis', $kd_analysis)->first();

        if(!is_null($check)){
            $kd_analysis = mt_rand();
        }else{
            $store_analysis = Analysis::create([
                'kd_analysis' => $kd_analysis,
                'month' => $month,
                'year' => $year,
                'min_support' => $min_support,
                'min_confidence' => $min_confidence,
                'created_at' => $datenow
            ]);
        }

        $totalProduk = Product::where('category_id', '!=', 3)->whereNull('deleted_at')->count();
        $dataProduk = Product::where('category_id', '!=', 3)->whereNull('deleted_at')->get();

        foreach($dataProduk as $produk){
            $id_product = $produk -> id;
            $totalTransaksi = Details::join('orders_new', 'orders_new.id', '=','details_order.id_order')
                            ->where('details_order.id_product', $id_product)
                            ->whereYear('orders_new.date', $year)
                            ->whereMonth('orders_new.date', $month)
                            ->whereNull('orders_new.deleted_at')
                            ->whereNull('details_order.deleted_at')
                            ->count();
            $nSupport = ($totalTransaksi / $totalProduk) * 100;

            $support_process = Support::create([
                'kd_analysis' => $kd_analysis,
                'id_product' => $id_product,
                'support' => $nSupport,
                'created_at' => $datenow
            ]);
        }

        // kombinasi 2 item set
        $qProdukA = Support::where('kd_analysis', $kd_analysis) -> where('support', '>=', $min_support) -> whereNull('deleted_at') -> get();
        foreach($qProdukA as $qProdA){
            $kdProdukA = $qProdA -> id_product;
            $qProdukB = Support::where('kd_analysis', $kd_analysis) -> where('support', '>=', $min_support) -> get();
            foreach($qProdukB as $qProdB){
                $kdProdukB = $qProdB -> id_product;
                $jumB = Kombinasi::where('id_product_a', $kdProdukB) -> count();
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
                            'id_product_b' => $kdProdukB,
                            'jumlah_transaksi' => 0,
                            'support' => 0,
                            'created_at' => $datenow
                        ]);
                    }
                }
            }
        }

        // kombinasi 2 itemset phase 2
        $nilaiKombinasi = Kombinasi::where('kd_analysis', $kd_analysis) -> whereNull('deleted_at') -> get();
        foreach($nilaiKombinasi as $nk){
            $kdKombinasi = $nk -> kd_kombinasi;
            $kdBarangA = $nk -> id_product_a;
            $kdBarangB = $nk -> id_product_b;

            // cari total transaksi
            $dataFaktur = Details::join('orders_new', 'orders_new.id', '=','details_order.id_order')
                            ->whereYear('orders_new.date', $year)
                            ->whereMonth('orders_new.date', $month)
                            ->whereNull('orders_new.deleted_at')
                            ->whereNull('details_order.deleted_at')
                            ->distinct()
                            ->get(['details_order.id_order']);
            $fnTransaksi = 0;
            foreach($dataFaktur as $faktur){
                $noFaktur = $faktur -> id_order;
                $qBonTransaksiA = Details::where('id_order', $noFaktur) -> where('id_product', $kdBarangA) -> count();
                $qBonTransaksiB = Details::where('id_order', $noFaktur) -> where('id_product', $kdBarangB) -> count();
                if($qBonTransaksiA == 1 && $qBonTransaksiB == 1){
                    $fnTransaksi++;
                }
            }
            $support = ($fnTransaksi / $totalProduk) * 100;
            $kombinasi_proses_2 = Kombinasi::where('kd_analysis', $kd_analysis) -> where('kd_kombinasi', $kdKombinasi) -> update([
                'jumlah_transaksi' => $fnTransaksi,
                'support' => $support
            ]);
        }

        if( $store_analysis && $support_process && $kombinasi_process_1 && $kombinasi_proses_2){
            $dataPengujian = Analysis::where('kd_analysis', $kd_analysis) -> first();
            $dataSupportProduk = Support::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
            $dataMinSupp = Support::where('kd_analysis', $kd_analysis) -> where('support', '>=', $min_support) -> orderBy('support', 'desc') -> get();
            $dataKombinasiItemset = Kombinasi::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
            $dataMinConfidence = Kombinasi::where('kd_analysis', $kd_analysis) -> where('support', '>=', $min_confidence) -> orderBy('support', 'desc') -> get();
            $totalProduk = Product::where('category_id', '!=', 3)->whereNull('deleted_at')->count();
            $data = [
                'success' => true,
                'title' => "Analysis of ".date("F", mktime(0, 0, 0, $month, 10))." ".$year."",
                'min_support' => $min_support,
                'min_confidence' => $min_confidence,
                'tahun' => $year,
                'bulan' => $month,
                'dataSupport' => $dataSupportProduk,
                'totalProduk' => $totalProduk,
                'dataPengujian' => $dataPengujian,
                'dataMinSupport' => $dataMinSupp,
                'dataKombinasiItemset' => $dataKombinasiItemset,
                'dataMinConfidence' => $dataMinConfidence,
                'kdPengujian' => $kd_analysis
            ];
            return view('analysis.create', $data)->with('success','Analysis process successfully!');
        }else{
            $dataPengujian = Analysis::where('kd_analysis', $kd_analysis) -> first();
            $dataSupportProduk = Support::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
            $dataMinSupp = Support::where('kd_analysis', $kd_analysis) -> where('support', '>=', $min_support) -> orderBy('support', 'desc') -> get();
            $dataKombinasiItemset = Kombinasi::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
            $dataMinConfidence = Kombinasi::where('kd_analysis', $kd_analysis) -> where('support', '>=', $min_confidence) -> orderBy('support', 'desc') -> get();
            $totalProduk = Product::where('category_id', '!=', 3)->whereNull('deleted_at')->count();
            $data = [
                'success' => true,
                'title' => "Analysis of ".date("F", mktime(0, 0, 0, $month, 10))." ".$year."",
                'min_support' => $min_support,
                'min_confidence' => $min_confidence,
                'tahun' => $year,
                'bulan' => $month,
                'dataSupport' => $dataSupportProduk,
                'totalProduk' => $totalProduk,
                'dataPengujian' => $dataPengujian,
                'dataMinSupport' => $dataMinSupp,
                'dataKombinasiItemset' => $dataKombinasiItemset,
                'dataMinConfidence' => $dataMinConfidence,
                'kdPengujian' => $kd_analysis
            ];
            return view('analysis.create', $data)->with('success','Analysis process successfully!');
        }
    }

    public function getMonth(Request $req){

        $months = Orders::selectRaw('MONTH(date) as bulan, MONTHNAME(date) as nama_bulan')
                ->whereYear('date', $req->tahun)
                ->where('deleted_at',null)
                ->groupBy('bulan')
                ->groupBy('nama_bulan')
                ->orderBy('bulan', 'asc')
                ->get();

        return json_encode($months);
    }

    public function detail($kd_analysis){
        $dataPengujian = Analysis::where('kd_analysis', $kd_analysis) -> first();
        $dataSupportProduk = Support::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
        $dataMinSupp = Support::where('kd_analysis', $kd_analysis) -> where('support', '>=', $dataPengujian->min_support) -> orderBy('support', 'desc') -> get();
        $dataKombinasiItemset = Kombinasi::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
        $dataMinConfidence = Kombinasi::where('kd_analysis', $kd_analysis) -> where('support', '>=', $dataPengujian->min_confidence) -> orderBy('support', 'desc') -> get();
        $totalProduk = Product::where('category_id', '!=', 3)->whereNull('deleted_at')->count();
        $data = [
            'title' => "Analysis of ".date("F", mktime(0, 0, 0, $dataPengujian->month, 10))." ".$dataPengujian->year."",
            'min_support' => $dataPengujian->min_support,
            'min_confidence' => $dataPengujian->min_confidence,
            'tahun' => $dataPengujian->year,
            'bulan' => $dataPengujian->month,
            'dataSupport' => $dataSupportProduk,
            'totalProduk' => $totalProduk,
            'dataPengujian' => $dataPengujian,
            'dataMinSupport' => $dataMinSupp,
            'dataKombinasiItemset' => $dataKombinasiItemset,
            'dataMinConfidence' => $dataMinConfidence,
            'kdPengujian' => $kd_analysis
        ];
        return view('analysis.create', $data);
    }

    public function delete(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $exec = Analysis::where('kd_analysis', $req->kd_analysis )->update([
            'deleted_at'=>$datenow,
            'updated_at'=>$datenow
        ]);

        if ($exec) {

            $exec_2 = Support::where('kd_analysis', $req->kd_analysis)->update([
                'deleted_at'=>$datenow,
                'updated_at'=>$datenow
            ]);

            $exec_3 = Kombinasi::where('kd_analysis', $req->kd_analysis)->update([
                'deleted_at'=>$datenow,
                'updated_at'=>$datenow
            ]);

            if ($exec_2 && $exec_3) {

                Session::flash('success', 'Data successfully deleted!');

            } else {

                Session::flash('gagal', 'Error Data');

            }

        } else {

            Session::flash('gagal', 'Error Data');

        }
    }

}
