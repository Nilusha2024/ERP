<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'tbl_order';

    public $fillable = ['id', 'orderno','center_status', 'reference_no', 'location_id', 'zonemanager_id','status'];


    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_id','id');
    }

}
