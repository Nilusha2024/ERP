<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovementHistory extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_stock_movement_history';

    public $fillable = ['location_id', 'item_id', 'qty', 'user_id','type'];

    public function location()
    {
        return $this->belongsTo('App\Models\Location','location_id','id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item','item_id','id');
    }

}
