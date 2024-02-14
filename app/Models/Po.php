<?php

namespace App\Models;

// warehouse table get data

use Illuminate\Database\Eloquent\Model;

class po extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_po';
    public $fillable = ['id', 'vendor_id', 'po_no', 'approved_by_finance', 'approved_by_ed', 'created_by', 'status'];


    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }

    public function podetails()
    {
        return $this->hasMany('App\Models\PoDetails', 'po_id');
    }
}
