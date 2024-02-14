<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ItemReturnDetails extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_return_detail';

    // prevents updated at and created at defaults
    public $timestamps = false;

    public $fillable = ['item_id','return_id', 'qty','serial_no' ,'status', 'returnFrom' , 'returnTo'];

    public function user()
    {
        return $this->belongsTo('App\Models\CategoryCode','users_id','id');
    }
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }

    public function itemreturn()
    {
        return $this->belongsTo('App\Models\ItemReturn', 'return_id', 'id');
    }

    public function returnfrom()
    {
        return $this->belongsTo('App\Models\Location', 'returnFrom', 'id');
    }

    public function returnto()
    {
        return $this->belongsTo('App\Models\Location', 'returnTo', 'id');
    }

}