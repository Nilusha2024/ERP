<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_location';
    public $fillable = ['code', 'location', 'status', 'warehouse_type_id'];

    public function type()
    {
        return $this->belongsTo('App\Models\warehouse_type','warehouse_type_id','id');
    }

}

