<?php

namespace App\Models;

// warehouse table get data

use Illuminate\Database\Eloquent\Model;

class GrnDetails extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */

     protected $table = 'tbl_grn_details';
     public $fillable = ['id', 'grn_id', 'item_id', 'qty', 'status', 'price'];

     public function item()
     {
          return $this->belongsTo('App\Models\Item', 'item_id', 'id');
     }
}
