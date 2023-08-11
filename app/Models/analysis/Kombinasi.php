<?php

namespace App\Models\analysis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\product\Product;

class Kombinasi extends Model
{
    protected $primaryKey = 'id';

    protected $table = "tbl_nilai_kombinasi";

    protected $fillable = [
        'kd_analysis',
        'kd_kombinasi',
        'id_product_a',
        'id_product_b',
        'jumlah_transaksi',
        'support'
    ];

    public function dataProduk($id_product)
    {
        return Product::where('id', $id_product) -> first();
    }

  }
