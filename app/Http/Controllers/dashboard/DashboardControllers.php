<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\order\Order;
use App\Models\order\Orders;
use App\Models\order\detail\Details;
use App\Models\product\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use DateTime;

class DashboardControllers extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index View and Scope Data
    public function index()
    {
        date_default_timezone_set("Asia/Bangkok");
        $yearz = date('Y');
        $monthz = date('m');

        $month = range(1,12);

        // RETURN DATA
        $data['title'] = "Dashboard";

        foreach($month as $mon){
            $dateObj   = DateTime::createFromFormat('!m', $mon);
            $monthName = $dateObj->format('F');
            $data['month'][]=array('month'=>$monthName);

            $income = Orders::whereYear('date', $yearz)
                    ->whereMonth('date', $mon)
                    ->whereNull('deleted_at')
                    ->where('orders_new.event_type', 'Payment')
                    ->selectRaw('sum(total_amount) as income')
                    ->groupBy(DB::raw('MONTH(date)'))
                    ->first();

            if($income){
                $data['incomepermonth'][] = array($income->income);
            }else{
                $data['incomepermonth'][] = array('0');
            }
        }

        $data['topproduct'] = Details::selectRaw('
                                product.id,
                                product.product_name,
                                SUM(qty) as total
                            ')
                            ->join('orders_new', 'orders_new.id', '=', 'details_order.id_order')
                            ->join('product', 'product.id', '=', 'details_order.id_product')
                            ->whereYear('orders_new.date', $yearz)
                            ->whereMonth('orders_new.date', $monthz)
                            ->where('orders_new.event_type', 'Payment')
                            ->whereNull('orders_new.deleted_at')
                            ->groupBy('product.id')
                            ->groupBy('product.product_name')
                            ->orderBy('total', 'desc')
                            ->limit(10)
                            ->get();

        // RETURN VIEW
        return view('dashboard', $data);
    }
}
