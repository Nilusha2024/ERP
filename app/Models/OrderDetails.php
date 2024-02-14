<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    protected $table = 'tbl_order_details';
    public $fillable = ['id','order_id','item_id','center_request_qty','store_approved_qty','center_received_qty','status'];

    // linking item code to item id
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}
