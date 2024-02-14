<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_stock';

    public $fillable = ['location_id', 'item_id', 'qty', 'balance','status'];

    public function location()
    {
        return $this->belongsTo('App\Models\Location','location_id','id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item','item_id','id');
    }

    public function serials()
    {
        return $this->hasMany('App\Models\Serial', 'stock_id', 'id');
    }

}
