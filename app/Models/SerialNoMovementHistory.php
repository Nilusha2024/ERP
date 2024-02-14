<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNoMovementHistory extends Model
{
    // table
    protected $table = 'tbl_serial_no_movement_history';

    // in regards to mass assignment rule
    protected $fillable = [
        'serial_no', 'item_id', 'location_id', 'user_id','type'
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }
}
