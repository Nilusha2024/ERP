<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'tbl_serial_no';

    public $fillable = ['serial_no', 'item_id', 'stock_id', 'status','location_id'];

    // prevents updated at and created at defaults
    public $timestamps = false;

    public function stock()
    {
        return $this->belongsTo('App\Models\Stock', 'stock_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item','item_id','id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location','location_id','id');
    }

  

}
