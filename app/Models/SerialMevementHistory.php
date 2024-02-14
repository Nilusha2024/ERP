<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerialMevementHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'tbl_serial_no_movement_history';

    public $fillable = ['serial_no', 'item_id','location_id','user_id', 'type'];

    // prevents updated at and created at defaults
    public $timestamps = false;


    public function item()
    {
        return $this->belongsTo('App\Models\Item','item_id','id');
    }

  

}
