<?php

namespace App\Models\order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $primaryKey = 'id';

      protected $table = "orders_new";

      protected $guarded = [];

      public $timestamps = false;
  }
