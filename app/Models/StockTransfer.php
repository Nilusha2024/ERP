<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    // enabling soft deletes
    use SoftDeletes;

    // table
    protected $table = 'tbl_stock_transfer';

    // in regards to mass assignment rule
    protected $fillable = [
        'tr_no',
        'created_by',
        'received_by',
        'mr_id',
        'from_location_id',
        'to_location_id',
        'type',
        'reason',
        'helpdeskno',
        'status',
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function receivedBy()
    {
        return $this->belongsTo('App\Models\User', 'received_by', 'id');
    }

    public function from()
    {
        return $this->belongsTo('App\Models\Location', 'from_location_id', 'id');
    }

    public function to()
    {
        return $this->belongsTo('App\Models\Location', 'to_location_id', 'id');
    }

    public function stdetails()
    {
        return $this->hasMany('App\Models\StockTransferDetails', 'transfer_id', 'id');
    }
    public function mr()
    {
        return $this->belongsTo('App\Models\Order', 'mr_id', 'id');
    }
}
