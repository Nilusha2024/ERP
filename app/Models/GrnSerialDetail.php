<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnSerialDetail extends Model
{
    // table
    protected $table = 'tbl_grn_serial_details';

    // in regards to mass assignment rule
    protected $fillable = [
        'grn_id',
        'grn_detail_id',
        'item_id',
        'serial_no',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }
}
