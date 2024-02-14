<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceCard extends Model
{
    protected $table = 'tbl_price_card';
    public $fillable = ['id', 'item_id', 'item_type', 'price', 'status'];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }
}
