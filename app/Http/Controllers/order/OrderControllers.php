<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Models\order\Orders;
use App\Models\order\detail\Details;
use App\Models\source_payment\Source;
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
use PDF;

class OrderControllers extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index View and Scope Data
    public function index()
    {
        return view('order.index', [
            "title" => "List Order",
            "years" => Orders::select(DB::raw('YEAR(date) as tahun'))->orderBy(DB::raw('YEAR(date)'))->where('deleted_at',null)->groupBy(DB::raw("YEAR(date)"))->get(),
            "months" => Orders::select(DB::raw('MONTH(date) as bulan'))->orderBy(DB::raw('MONTH(date)'))->where('deleted_at',null)->groupBy(DB::raw("MONTH(date)"))->get(),
            "orders" => Orders::orderBy('date', 'DESC')->where('deleted_at',null)->get()
        ]);
    }

    public function getMonth(Request $req){
        $months = Orders::select(DB::raw('MONTH(date) as bulan, MONTHNAME(date) as nama_bulan'))->whereYear('date', $req->tahun)->orderBy(DB::raw('MONTH(date)'))->where('deleted_at',null)->groupBy(DB::raw("MONTHNAME(date)"))->groupBy(DB::raw("MONTH(date)"))->get();
        return json_encode($months);
    }

    // Create View Data
    public function create()
    {
        $data['title'] = "Add Order";
        $data['url'] = 'store';
        $data['disabled_'] = '';
        $data['disabled__'] = 'disabled';
        $data['category'] = Category::where('deleted_at',null)->orderBy('category', 'asc')->get();
        $data['payment_method'] = PaymentMethod::where('deleted_at',null)->orderBy('payment_method', 'asc')->get();
        $data['products'] = Product::where('deleted_at',null)->orderBy('product_name', 'asc')->get();
        return view('order.create', $data);
    }

    // get Detail Product View Data
    public function getDetailProds(Request $req)
    {
        $data["prods"] = Product::where("id", $req->id_prod)->first();
        return $data["prods"];
    }

    // Store Function to Database
    public function store(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');

        if($req->event_type == "Payment"){
            $order_pay = Orders::create([
                'receipt_number' => $req->receipt_number,
                'date' => $req->tgl,
                'time' => $req->time,
                'event_type' => $req->event_type,
                'payment_method' => $req->payment_method,
                'discount' => $req->discount,
                'total_amount' => $req->total_amount,
                'created_at' => $datenow,
                'created_by' => Auth::user()->id
            ]);

        }else{
            $order_pay = Orders::create([
                'receipt_number' => $req->receipt_number,
                'date' => $req->tgl,
                'time' => $req->time,
                'event_type' => $req->event_type,
                'payment_method' => $req->payment_method,
                'refund' => $req->total_amount,
                'total_amount' => $req->total_amount,
                'created_at' => $datenow,
                'created_by' => Auth::user()->id
            ]);

        }

        if($order_pay){

            $qty = $req->qty;
            $products = $req->product_id;

            foreach($products as $key=>$prods){

                $order_pay = Details::create([
                    'id_order' => $order_pay->id,
                    'id_product' => $prods,
                    'qty' => $qty[$key],
                    'created_at' => $datenow
                ]);

            }


        }

        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.order.index')->with(['success' => 'Data successfully stored!']);
        }else{
            return redirect()->route('user.order.index')->with(['success' => 'Data successfully stored!']);
        }
    }

    // Detail Data View by id
    public function detail($id)
    {
        $data['title'] = "Detail Order";
        $data['disabled_'] = 'disabled';
        $data['disabled__'] = '';
        $data['url'] = 'create';
        $data['orders'] = Orders::where('id', $id)->first();
        $data['products'] = Product::orderBy('product_name', 'asc')->get();
        return view('order.create', $data);
    }

    // Edit Data View by id
    public function edit($id)
    {
        $data['title'] = "Edit Order";
        $data['disabled_'] = '';
        $data['disabled__'] = '';
        $data['url'] = 'update';
        $data['orders'] = Order::where('id', $id)->first();
        $data['products'] = Product::orderBy('product_name', 'asc')->where('status', 'Active')->get();
        $data['sources'] = Source::orderBy('id', 'asc')->get();
        return view('order.create', $data);
    }

    // Update Function to Database
    public function update(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $order_pay = Order::where('id', $req->id)->update([
            'product_id' => $req->prods,
            'qty' => $req->qty,
            'entry_price' => $req->entry_price,
            'source_id' => $req->source_pay,
            'date' => $req->tgl,
            'note' => $req->note,
            'tax' => $req->cal_tax,
            'profit' => $req->cal_profit,
            'updated_at' => $datenow,
            'updated_by' => Auth::user()->id
        ]);

        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.order.index')->with(['success' => 'Data successfully updated!']);
        }else{
            return redirect()->route('user.order.index')->with(['success' => 'Data successfully updated!']);
        }
    }

    // Delete Data Function
    public function delete(Request $req)
    {
        $datenow = date('Y-m-d H:i:s');
        $exec = Orders::where('id', $req->id )->update([
            'deleted_at'=>$datenow,
            'updated_at'=>$datenow,
            'updated_by'=>Auth::user()->id
        ]);

        if ($exec) {

            $exec_2 = Details::where('id_order', $req->id )->update([
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

    // Index View and Scope Data
    public function export(Request $req)
    {
        if($req->bulan==0){
            $orders= Order::whereYear('date', $req->tahun)->orderBy('date', 'ASC')->get();
            $sum= Order::selectRaw(DB::raw("SUM(base_price_product) as total_base, SUM(qty) as total_qty, SUM(tax) as total_tax, SUM(profit) as total_profit"))->whereYear('date', $req->tahun)->first();
            $data =  [
                'success' => 'success',
                'orders' => $orders,
                'sum' => $sum,
                'year' => $req->tahun
            ];
            return Excel::download(new ReportOrderExport($data), 'Reports_Order_'.$req->tahun.'.xlsx');
        }else{
            $orders= Order::whereMonth('date', $req->bulan)->whereYear('date', $req->tahun)->get();
            $sum= Order::selectRaw(DB::raw("SUM(base_price_product) as total_base, SUM(qty) as total_qty, SUM(tax) as total_tax, SUM(profit) as total_income, SUM(profit) as total_profit"))->whereMonth('date', $req->bulan)->whereYear('date', $req->tahun)->orderBy('date', 'ASC')->first();
            $data =  [
                'success' => 'success',
                'orders' => $orders,
                'sum' => $sum,
                'year' => $req->tahun,
                'month' => $req->bulan
            ];
            return Excel::download(new ReportOrderExport($data), 'Reports_Order_'.date("F", mktime(0, 0, 0, $req->month, 10)).'_'.$req->tahun.'.xlsx');
        }

    }
}
