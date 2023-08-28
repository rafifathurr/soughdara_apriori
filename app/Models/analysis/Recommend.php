<?php

namespace App\Models\analysis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\product\Product;

class Recommend extends Model
{
    protected $primaryKey = 'id';

      protected $table = "tbl_package_recommend";

      protected $guarded = [];

      public $timestamps = false;

      public function dataProduk($id_product)
        {
            return Product::where('id', $id_product) -> first();
        }
  }
