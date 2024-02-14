<?php

namespace App\Models;

// warehouse table get data

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
   
    protected $table = 'tbl_grn';
    public $fillable = ['id','grn_no','po_id', 'ref_no','created_by','status'];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function grndetails()
    {
        return $this->hasMany('App\Models\GrnDetails', 'grn_id');
    }

    public function po()
    {
        return $this->belongsTo('App\Models\Po', 'po_id', 'id');
    }
   
}
