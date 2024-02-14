<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_item';
    public $fillable = ['item_no', 'category_code_id', 'uom_id', 'item_type_id', 'description', 'users_id', 'status', 'mr_status', 'item_type'];

    public function uom()
    {
        return $this->belongsTo('App\Models\Uom', 'uom_id', 'id');
    }

    public function categorycode()
    {
        return $this->belongsTo('App\Models\CategoryCode', 'category_code_id', 'id');
    }

    public function price()
    {
        return $this->hasMany('App\Models\PriceCard', 'item_id')->orderBy('id', 'DESC');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\CategoryCode', 'users_id', 'id');
    }

    public function returnTo()
    {
        return $this->belongsTo('App\Models\Location', 'return_to', 'id');
    }

    public function serials()
    {
        return $this->hasMany('App\Models\Serial', 'item_id', 'id');
    }


   
}
