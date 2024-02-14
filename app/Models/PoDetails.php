<?php

namespace App\Models;

// warehouse table get data

use Illuminate\Database\Eloquent\Model;

class PoDetails extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'tbl_po_details';
    public $fillable = ['id', 'po_id', 'item_id', 'qty', 'price', 'status'];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }

    public function po()
    {
        return $this->belongsTo('App\Models\Po', 'po_id', 'id');
    }
}
