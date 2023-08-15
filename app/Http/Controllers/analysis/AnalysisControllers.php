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
        $year_now = date('Y');
        $years = Orders::selectRaw('YEAR(date) as year')
                ->whereNull('deleted_at')
                ->groupBy('year')
                ->get();
        $analysis = Analysis::whereNull('deleted_at')->orderBy('month', 'desc')->orderBy('year', 'desc')->orderBy('id','desc')->get();
        $get_min = Orders::select('date')
                    ->whereNull('deleted_at')
                    ->whereYear('date', $year_now)
                    ->orderBy('date', 'asc')
                    ->first();
        $get_max = Orders::select('date')
                    ->whereNull('deleted_at')
                    ->whereYear('date', $year_now)
                    ->orderBy('date', 'desc')
                    ->first();

        return view('analysis.index', [
            "title" => "Analysis Process",
            "years" => $years,
            "analysis" => $analysis,
            "min_date" => $get_min->date,
            "max_date" => $get_max->date
        ]);
    }
    public function create($date, $min_support, $min_confidence)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $yeardate = date('Y', strtotime($date));
        $kd_analysis = mt_rand();
        $check = Analysis::where('kd_analysis', $kd_analysis)->first();

        if(!is_null($check)){
            $kd_analysis = mt_rand();
        }else{
            $store_analysis = Analysis::create([
                'kd_analysis' => $kd_analysis,
                'year' => $yeardate,
                'date' => $date,
                'min_support' => $min_support,
                'min_confidence' => $min_confidence,
                'created_at' => $datenow
            ]);
        }

        $totalProduk = Details::selectRaw('details_order.id_order')->join('orders_new', 'orders_new.id', '=','details_order.id_order')
                    ->join('product', 'product.id', '=', 'details_order.id_product')
                    ->where('product.category_id', '!=', 3)
                    ->wheredate('orders_new.date', $date)
                    ->whereNull('orders_new.deleted_at')
                    ->whereNull('details_order.deleted_at')
                    ->groupBy('details_order.id_order')
                    ->get();
        $totalProduk = count($totalProduk);
        $dataProduk = Product::where('category_id', '!=', 3)->whereNull('deleted_at')->get();

        foreach($dataProduk as $produk){
            $id_product = $produk -> id;
            $totalTransaksi = Details::join('orders_new', 'orders_new.id', '=','details_order.id_order')
                            ->where('details_order.id_product', $id_product)
                            ->wheredate('orders_new.date', $date)
                            ->whereNull('orders_new.deleted_at')
                            ->whereNull('details_order.deleted_at')
                            ->count();
            $nSupport = ($totalTransaksi / $totalProduk) * 100;

            if($nSupport != 0){
                $support_process = Support::create([
                    'kd_analysis' => $kd_analysis,
                    'id_product' => $id_product,
                    'support' => $nSupport,
                    'total_transaksi' => $totalTransaksi,
                    'created_at' => $datenow
                ]);
            }
        }

        // kombinasi 2 item set
        $qProdukA = Support::where('kd_analysis', $kd_analysis) -> where('support', '>=', $min_support) -> whereNull('deleted_at') -> get();
        foreach($qProdukA as $qProdA){
            $kdProdukA = $qProdA -> id_product;
            $transaksiProdukA = $qProdA -> total_transaksi;
            $qProdukB = Support::where('kd_analysis', $kd_analysis) -> whereNull('deleted_at') -> get();
            foreach($qProdukB as $qProdB){
                $kdProdukB = $qProdB -> id_product;
                if($kdProdukA != $kdProdukB){

                    $kdKombinasi = mt_rand();
                    $check = Kombinasi::where('kd_kombinasi', $kdKombinasi)->first();

                    if(!is_null($check)){
                        $kdKombinasi = mt_rand();
                    }

                    if($kdKombinasi == $kd_analysis){
                        $kdKombinasi = mt_rand();
                    }

                    // if($transaksiProdukA != 0){
                    //     $kombinasi_process_1 = Kombinasi::create([
                    //         'kd_analysis' => $kd_analysis,
                    //         'kd_kombinasi' => $kdKombinasi,
                    //         'id_product_a' => $kdProdukA,
                    //         'id_product_b' => $kdProdukB,
                    //         'jumlah_transaksi' => 0,
                    //         'total_transaksi_product_a' => $transaksiProdukA,
                    //         'support' => 0,
                    //         'confidence' => 0,
                    //         'created_at' => $datenow
                    //     ]);
                    // }

                    $dataFaktur = Details::join('orders_new', 'orders_new.id', '=','details_order.id_order')
                            ->wheredate('orders_new.date', $date)
                            ->whereNull('orders_new.deleted_at')
                            ->whereNull('details_order.deleted_at')
                            ->distinct()
                            ->get(['details_order.id_order']);

                    $fnTransaksi = 0;

                    foreach($dataFaktur as $faktur){
                        $noFaktur = $faktur -> id_order;
                        $qBonTransaksiA = Details::where('id_order', $noFaktur) -> where('id_product', $kdProdukA) -> count();
                        $qBonTransaksiB = Details::where('id_order', $noFaktur) -> where('id_product', $kdProdukB) -> count();
                        if($qBonTransaksiA == 1 && $qBonTransaksiB == 1){
                            $fnTransaksi++;
                        }
                    }

                    if($transaksiProdukA != 0){
                        if($fnTransaksi != 0){
                            $support = ($fnTransaksi / $totalProduk) * 100;
                            // $kombinasi_proses_2 = Kombinasi::where('kd_analysis', $kd_analysis) -> where('kd_kombinasi', $kdKombinasi) -> update([
                            //     'jumlah_transaksi' => $fnTransaksi,
                            //     'support' => $support
                            // ]);
                            if($transaksiProdukA != 0){
                                $confidence = ($fnTransaksi / $transaksiProdukA) * 100;
                                // $kombinasi_proses_3 = Kombinasi::where('kd_analysis', $kd_analysis) -> where('kd_kombinasi', $kdKombinasi) -> update([
                                //     'confidence' => $confidence
                                // ]);
                                $kombinasi_process = Kombinasi::create([
                                    'kd_analysis' => $kd_analysis,
                                    'kd_kombinasi' => $kdKombinasi,
                                    'id_product_a' => $kdProdukA,
                                    'id_product_b' => $kdProdukB,
                                    'jumlah_transaksi' => $fnTransaksi,
                                    'total_transaksi_product_a' => $transaksiProdukA,
                                    'support' => $support,
                                    'confidence' => $confidence,
                                    'created_at' => $datenow
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // kombinasi 2 itemset phase 1 (support)
        // $nilaiKombinasi = Kombinasi::where('kd_analysis', $kd_analysis) -> whereNull('deleted_at') -> get();
        // foreach($nilaiKombinasi as $nk){
        //     $kdKombinasi = $nk -> kd_kombinasi;
        //     $kdBarangA = $nk -> id_product_a;
        //     $kdBarangB = $nk -> id_product_b;
        //     $transaksiProdukA = $nk -> total_transaksi_product_a;

        //     // cari total transaksi

        // }

        if( $store_analysis && $support_process && $kombinasi_process){
            $dataPengujian = Analysis::where('kd_analysis', $kd_analysis) -> first();
            $dataSupportProduk = Support::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
            $dataKombinasiItemset = Kombinasi::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
            $dataKombinasiItemsetConfidence = Kombinasi::where('kd_analysis', $kd_analysis) -> orderBy('confidence', 'desc') -> get();
            $dataMinConfidence = Kombinasi::where('kd_analysis', $kd_analysis) -> where('support', '>=', $min_support) -> where('confidence', '>=', $min_confidence) -> orderBy('support', 'desc') -> orderBy('confidence', 'desc') -> get();
            $totalProduk = Details::selectRaw('details_order.id_order')->join('orders_new', 'orders_new.id', '=','details_order.id_order')
                    ->join('product', 'product.id', '=', 'details_order.id_product')
                    ->where('product.category_id', '!=', 3)
                    ->wheredate('orders_new.date', $date)
                    ->whereNull('orders_new.deleted_at')
                    ->whereNull('details_order.deleted_at')
                    ->groupBy('details_order.id_order')
                    ->get();
            $totalProduk = count($totalProduk);
            $data = [
                'success' => true,
                'title' => "Analysis of ".$date."",
                'min_support' => $min_support,
                'min_confidence' => $min_confidence,
                'date' => $date,
                'dataSupport' => $dataSupportProduk,
                'totalProduk' => $totalProduk,
                'dataPengujian' => $dataPengujian,
                'dataKombinasiItemset' => $dataKombinasiItemset,
                'dataKombinasiItemsetConfidence' => $dataKombinasiItemsetConfidence,
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
        $dataKombinasiItemset = Kombinasi::where('kd_analysis', $kd_analysis) -> orderBy('support', 'desc') -> get();
        $dataKombinasiItemsetConfidence = Kombinasi::where('kd_analysis', $kd_analysis) -> orderBy('confidence', 'desc') -> get();
        $dataMinConfidence = Kombinasi::where('kd_analysis', $kd_analysis) -> where('support', '>=', $dataPengujian->min_support) -> where('confidence', '>=', $dataPengujian->min_confidence) -> orderBy('support', 'desc') -> orderBy('confidence', 'desc') -> get();
        $totalProduk = Details::selectRaw('details_order.id_order')->join('orders_new', 'orders_new.id', '=','details_order.id_order')
                    ->join('product', 'product.id', '=', 'details_order.id_product')
                    ->where('product.category_id', '!=', 3)
                    ->wheredate('orders_new.date', $dataPengujian->date)
                    ->whereNull('orders_new.deleted_at')
                    ->whereNull('details_order.deleted_at')
                    ->groupBy('details_order.id_order')
                    ->get();
        $totalProduk = count($totalProduk);
        $data = [
            'title' => "Analysis of ".$dataPengujian->date."",
            'min_support' => $dataPengujian->min_support,
            'min_confidence' => $dataPengujian->min_confidence,
            'date' => $dataPengujian->date,
            'dataSupport' => $dataSupportProduk,
            'totalProduk' => $totalProduk,
            'dataPengujian' => $dataPengujian,
            'dataKombinasiItemset' => $dataKombinasiItemset,
            'dataKombinasiItemsetConfidence' => $dataKombinasiItemsetConfidence,
            'dataMinConfidence' => $dataMinConfidence,
            'kdPengujian' => $kd_analysis
        ];
        return view('analysis.create', $data);
    }

    public function delete(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $exec = Analysis::where('kd_analysis', $req->kd_analysis )->delete();

        if ($exec) {

            $exec_2 = Support::where('kd_analysis', $req->kd_analysis)->delete();

            $exec_3 = Kombinasi::where('kd_analysis', $req->kd_analysis)->delete();

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
