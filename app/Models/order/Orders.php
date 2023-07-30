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
      public function createdby()
      {
        return $this->belongsTo('App\Models\users\User', 'created_by', 'id');
      }

      public function updatedby()
      {
        return $this->belongsTo('App\Models\users\User', 'updated_by', 'id');
      }
  }
