<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\product\Product;
use App\Models\category\Category;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Auth;
use Session;

class ProductControllers extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index View and Scope Data
    public function index()
    {
        return view('product.index', [
            "title" => "List Products",
            "products" => Product::where('deleted_at',null)->get()
        ]);
    }

    // Create View Data
    public function create()
    {
        $data['title'] = "Add Products";
        $data['url'] = 'store';
        $data['disabled_'] = '';
        $data['categories'] = Category::orderBy('category', 'asc')->get();
        return view('product.create', $data);
    }

    // Store Function to Database
    public function store(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');

        $product_pay = Product::create([
            'product_name' => $req->name,
            'category_id' => $req->category,
            'price' => $req->price,
            'desc' => $req->desc,
            'created_at' => $datenow,
            'created_by' => Auth::user()->id
        ]);

        $destination='Uploads/Product/';
        if ($req->hasFile('uploads')) {
            $file = $req->file('uploads');
            $name_file = time().'_'.$req->file('uploads')->getClientOriginalName();
            Storage::disk('Uploads')->putFileAs($destination,$file,$name_file);
            Product::where('id', $product_pay->id)->update(['upload' => $name_file]);
        }

        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.product.index')->with(['success' => 'Data successfully stored!']);
        }else{
            return redirect()->route('user.product.index')->with(['success' => 'Data successfully stored!']);
        }


    }

    // Detail Data View by id
    public function detail($id)
    {
        $data['title'] = "Detail Products";
        $data['disabled_'] = 'disabled';
        $data['url'] = 'create';
        $data['products'] = Product::where('id', $id)->first();
        $data['categories'] = Category::all();
        return view('product.create', $data);
    }

    // Edit Data View by id
    public function edit($id)
    {
        $data['title'] = "Edit Products";
        $data['disabled_'] = '';
        $data['url'] = 'update';
        $data['products'] = Product::where('id', $id)->first();
        $data['categories'] = Category::all();
        return view('product.create', $data);
    }

    // Update Function to Database
    public function update(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $product_pay = Product::where('id', $req->id)->update([
            'product_name' => $req->name,
            'category_id' => $req->category,
            'price' => $req->price,
            'desc' => $req->desc,
            'updated_at' => $datenow,
            'updated_by' => Auth::user()->id
        ]);

        $destination='Uploads/Product/';
        if ($req->hasFile('uploads')) {
            $file = $req->file('uploads');
            $name_file = time().'_'.$req->file('uploads')->getClientOriginalName();
            Storage::disk('Uploads')->putFileAs($destination,$file,$name_file);
            Product::where('id', $req->id)->update(['upload' => $name_file]);
          }

        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.product.index')->with(['success' => 'Data successfully updated!']);
        }else{
            return redirect()->route('user.product.index')->with(['success' => 'Data successfully updated!']);
        }
    }

    // Delete Data Function
    public function delete(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $datenow = date('Y-m-d H:i:s');
        $exec = Product::where('id', $req->id )->update([
            'deleted_at'=>$datenow,
            'updated_at'=>$datenow,
            'updated_by'=>Auth::user()->id
        ]);

        if ($exec) {
            Session::flash('success', 'Data successfully deleted!');
          } else {
            Session::flash('gagal', 'Error Data');
          }
    }

    public function detailproduct(Request $req)
    {
        $data = Product::with('category')->where("id", $req->id_prod)->first();
        return $data;
    }
}
