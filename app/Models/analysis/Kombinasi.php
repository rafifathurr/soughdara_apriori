<?php

namespace App\Models\analysis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kombinasi extends Model
{
    protected $primaryKey = 'id';

      protected $table = "tbl_nilai_kombinasi";

      protected $guarded = [];

      public $timestamps = false;
  
  }
