<?php

namespace App\Http\Controllers\payment_method;

use App\Http\Controllers\Controller;
use App\Models\payment_method\PaymentMethod;

use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use PDF;

class PaymentMethodController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Index View and Scope Data
    public function index()
    {
        return view('payment_method.index', [
            "title" => "List Payment Method",
            "payment_methods" => PaymentMethod::where('deleted_at',null)->get()
        ]);
    }

    // Create View Data
    public function create()
    {
        $data['title'] = "Add Payment Method";
        $data['url'] = 'store';
        $data['disabled_'] = '';
        return view('payment_method.create', $data);
    }

    // Store Function to Database
    public function store(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $source_pay = PaymentMethod::create([  
            'payment_method' => $req->payment_method,
            'note' => $req->note,
            'created_at' => $datenow
        ]);

        return redirect()->route('admin.payment_method.index')->with(['success' => 'Data successfully stored!']);
    }

    // Detail Data View by id
    public function detail($id)
    {
        $data['title'] = "Detail Payment Method";
        $data['disabled_'] = 'disabled'; 
        $data['url'] = 'create';   
        $data['payment_methods'] = PaymentMethod::where('id', $id)->first();
        return view('payment_method.create', $data);
    }

    // Edit Data View by id
    public function edit($id)
    {
        $data['title'] = "Edit Payment Method";
        $data['disabled_'] = ''; 
        $data['url'] = 'update';   
        $data['payment_methods'] = PaymentMethod::where('id', $id)->first();
        return view('payment_method.create', $data);
    }

    // Update Function to Database
    public function update(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $source_pay = PaymentMethod::where('id', $req->id)->update([  
            'payment_method' => $req->payment_method,
            'note' => $req->note,
            'updated_at' => $datenow
        ]);

        return redirect()->route('admin.payment_method.index')->with(['success' => 'Data successfully updated!']);
    }

    // Delete Data Function
    public function delete(Request $req)
    {
        $datenow = date('Y-m-d H:i:s');
        $exec = PaymentMethod::where('id', $req->id )->update([
            'updated_at'=> $datenow,
            'deleted_at'=> $datenow
        ]);

        if ($exec) {
            Session::flash('success', 'Data successfully deleted!');
          } else {
            Session::flash('gagal', 'Error Data');
          }
    }


}
