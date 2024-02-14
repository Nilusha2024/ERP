<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferSerial extends Model
{
    // table
    protected $table = 'tbl_stock_transfer_serial';

    // in regards to mass assignment rule
    protected $fillable = [
        'transfer_id',
        'transfer_details_id',
        'item_id',
        'serial_no',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }
}
