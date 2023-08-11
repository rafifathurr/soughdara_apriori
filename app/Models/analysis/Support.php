<?php

namespace App\Models\analysis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\product\Product;
use App\Models\order\detail\Details;

class Support extends Model
{
    protected $primaryKey = 'id';

    protected $table = "tbl_support";

    protected $fillable = [
        'kd_analysis',
        'id_product',
        'support'
    ];

    public function dataProduk($id_product)
    {
        return Product::where('id', $id_product) -> first();
    }

    public function totalTransaksi($id_product)
    {
        return Details::where('id_product', $id_product) -> whereNull('deleted_at') -> count();
    }

  }
