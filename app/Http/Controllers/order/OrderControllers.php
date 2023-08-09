<?php

namespace App\Http\Controllers\order;

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
            "orders" => Orders::orderBy('date', 'DESC')->orderBy('time', 'DESC')->where('deleted_at',null)->get()
        ]);
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

    // Create View Data
    public function create()
    {
        $data['title'] = "Add Order";
        $data['url'] = 'store';
        $data['disabled_'] = '';
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

                $orders = Details::create([
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
        $data['url'] = 'create';
        $data['orders'] = Orders::with('createdby', 'updatedby')->where('id', $id)->first();
        $data['details_order'] = Details::with('product', 'product.category')->where('id_order', $id)->get();
        $data['payment_method'] = PaymentMethod::where('deleted_at',null)->orderBy('payment_method', 'asc')->get();
        return view('order.create', $data);
    }

    // Edit Data View by id
    public function edit($id)
    {
        $data['title'] = "Edit Order";
        $data['disabled_'] = '';
        $data['url'] = 'update';
        $data['orders'] = Orders::with('createdby', 'updatedby')->where('id', $id)->first();
        $data['details_order'] = Details::with('product', 'product.category')->where('id_order', $id)->get();
        $data['category'] = Category::where('deleted_at',null)->orderBy('category', 'asc')->get();
        $data['payment_method'] = PaymentMethod::where('deleted_at',null)->orderBy('payment_method', 'asc')->get();
        $data['products'] = Product::where('deleted_at',null)->orderBy('product_name', 'asc')->get();
        return view('order.create', $data);
    }

    // Update Function to Database
    public function update(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        if($req->event_type == "Payment"){
            $order_pay = Orders::where('id', $req->id)
                        ->update([
                        'receipt_number' => $req->receipt_number,
                        'date' => $req->tgl,
                        'time' => $req->time,
                        'event_type' => $req->event_type,
                        'payment_method' => $req->payment_method,
                        'discount' => $req->discount,
                        'total_amount' => $req->total_amount,
                        'updated_at' => $datenow,
                        'updated_by' => Auth::user()->id
                    ]);

        }else{
            $order_pay = Orders::where('id', $req->id)
                        ->update([
                            'receipt_number' => $req->receipt_number,
                            'date' => $req->tgl,
                            'time' => $req->time,
                            'event_type' => $req->event_type,
                            'payment_method' => $req->payment_method,
                            'refund' => $req->total_amount,
                            'total_amount' => $req->total_amount,
                            'updated_at' => $datenow,
                            'updated_by' => Auth::user()->id
                        ]);

        }

        if($order_pay){

            $qty = $req->qty;
            $products = $req->product_id;

            $exec = Details::where('id_order', $req->id )->delete();

            if($exec){
                foreach($products as $key=>$prods){

                    $orders = Details::create([
                        'id_order' => $req->id,
                        'id_product' => $prods,
                        'qty' => $qty[$key],
                        'created_at' => $datenow
                    ]);

                }
            }

        }

        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.order.index')->with(['success' => 'Data successfully updated!']);
        }else{
            return redirect()->route('user.order.index')->with(['success' => 'Data successfully updated!']);
        }
    }

    // Delete Data Function
    public function delete(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
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
        date_default_timezone_set("Asia/Bangkok");
        if($req->bulan==0){
            $check= Orders::join('payment_method', 'payment_method.id', '=', 'orders_new.payment_method')
                    ->selectRaw('
                        orders_new.id,
                        orders_new.receipt_number,
                        orders_new.date,
                        orders_new.time,
                        orders_new.refund,
                        orders_new.discount,
                        orders_new.event_type,
                        payment_method.payment_method,
                        orders_new.total_amount
                    ')
                    ->whereYear('date', $req->tahun)
                    ->whereNull('orders_new.deleted_at')
                    ->orderBy('date', 'ASC')
                    ->orderBy('time', 'ASC')
                    ->get();

            $sum = Orders::selectRaw("SUM(total_amount) as total")->where("event_type", "Payment")->whereYear('date', $req->tahun)->first();
            $refund = Orders::selectRaw("SUM(total_amount) as total")->where("event_type", "Refund")->whereYear('date', $req->tahun)->first();

            $total = $sum->total - $refund->total;

            $json = array();
            foreach($check as $order){

                $json[$order->id] = array(
                    'receipt_number' => $order->receipt_number,
                    'date' => $order->date,
                    'time' => $order->time,
                    'refund' => $order->refund,
                    'discount' => $order->discount,
                    'event_type' => $order->event_type,
                    'payment_method' => $order->payment_method,
                    'total_amount' => $order->total_amount,
                    'product' => array()
                );

                $product_order = Details::join('product', 'product.id', '=', 'details_order.id_product')
                                ->selectRaw('
                                    product.product_name,
                                    details_order.qty
                                ')
                                ->where('id_order', $order->id)
                                ->get();

                $detail = array();
                foreach($product_order as $product){
                    $detail = array(
                        'product_name' => $product->product_name,
                        'qty' => $product->qty
                    );
                    $json[$order->id]['product'][] = $detail;
                }

            }

            $orders = $json;

            $data =  [
                'success' => 'success',
                'orders' => $orders,
                'sum' => $total,
                'year' => $req->tahun
            ];

            return Excel::download(new ReportOrderExport($data), 'Reports_Order_'.$req->tahun.'.xlsx');
        }else{

            $check = Orders::join('payment_method', 'payment_method.id', '=', 'orders_new.payment_method')
                    ->selectRaw('
                        orders_new.id,
                        orders_new.receipt_number,
                        orders_new.date,
                        orders_new.time,
                        orders_new.refund,
                        orders_new.discount,
                        orders_new.event_type,
                        payment_method.payment_method,
                        orders_new.total_amount
                    ')
                    ->whereYear('date', $req->tahun)
                    ->whereMonth('date', $req->bulan)
                    ->whereNull('orders_new.deleted_at')
                    ->orderBy('date', 'ASC')
                    ->orderBy('time', 'ASC')
                    ->get();

            $sum = Orders::selectRaw("SUM(total_amount) as total")->where("event_type", "Payment")->whereYear('date', $req->tahun)->whereMonth('date', $req->bulan)->first();
            $refund = Orders::selectRaw("SUM(total_amount) as total")->where("event_type", "Refund")->whereYear('date', $req->tahun)->whereMonth('date', $req->bulan)   ->first();

            $total = $sum->total - $refund->total;

            $json = array();
            foreach($check as $order){

                $json[$order->receipt_number] = array(
                    'receipt_number' => $order->receipt_number,
                    'date' => $order->date,
                    'time' => $order->time,
                    'refund' => $order->refund,
                    'discount' => $order->discount,
                    'event_type' => $order->event_type,
                    'payment_method' => $order->payment_method,
                    'total_amount' => $order->total_amount,
                    'product' => array()
                );

                $product_order = Details::join('product', 'product.id', '=', 'details_order.id_product')
                                ->selectRaw('
                                    product.product_name,
                                    details_order.qty
                                ')
                                ->where('id_order', $order->id)
                                ->get();

                $detail = array();
                foreach($product_order as $product){
                    $detail = array(
                        'product_name' => $product->product_name,
                        'qty' => $product->qty
                    );
                    $json[$order->receipt_number]['product'][] = $detail;
                }

            }

            $orders = $json;

            $data =  [
                'success' => 'success',
                'orders' => $orders,
                'sum' => $total,
                'year' => $req->tahun,
                'month' => $req->bulan
            ];
            return Excel::download(new ReportOrderExport($data), 'Reports_Order_'.date("F", mktime(0, 0, 0, $req->bulan, 10)).'_'.$req->tahun.'.xlsx');
        }

    }
}
