<?php

namespace App\Models\analysis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $primaryKey = 'id';

      protected $table = "tbl_support";

      protected $guarded = [];

      public $timestamps = false;

      public function product()
      {
        return $this->belongsTo('App\Models\product\Product', 'id_product', 'id');
      }
  
  }
