<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferDetails extends Model
{
    // table
    protected $table = 'tbl_stock_transfer_details';

    // in regards to mass assignment rule
    protected $fillable = [
        'transfer_id',
        'item_id',
        'qty',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }

    public function stocktransfer()
    {
        return $this->belongsTo('App\Models\StockTransfer', 'transfer_id', 'id');
    }
}
