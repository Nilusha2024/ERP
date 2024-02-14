<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ItemReturn extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_return';

    public $fillable = ['item_no','return_no', 'created_by', 'status'];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');   
     }

     public function returnFrom()
     {
         return $this->belongsTo('App\Models\Location', 'return_from', 'id');
     }
 
     public function returnTo()
     {
         return $this->belongsTo('App\Models\Location', 'return_to', 'id');
     }

     public function location()
     {
         return $this->belongsTo('App\Models\Location', 'location_id', 'id');
     }

     public function serial()
    {
        return $this->belongsTo('App\Models\Item','serial_id','id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }
}
