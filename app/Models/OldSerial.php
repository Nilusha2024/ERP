<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldSerial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'tbl_old_serial_no';

    public $fillable = ['serial_no', 'item_no', 'location_code'];

    // prevents updated at and created at defaults
    public $timestamps = false;

    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_code', 'code');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item','item_no','item_no');
    }

  

}
