<?php

namespace App\Models\analysis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsAnalysis extends Model
{
    protected $primaryKey = 'id';

      protected $table = "details_analysis";

      protected $guarded = [];

      public $timestamps = false;
  }
