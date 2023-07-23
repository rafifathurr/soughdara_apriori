<?php

namespace App\Models\payment_method;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $primaryKey = 'id';
  
      protected $table = "payment_method";
  
      protected $guarded = [];

      public $timestamps = false;
  }
