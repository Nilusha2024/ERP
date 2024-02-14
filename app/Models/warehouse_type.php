<?php

namespace App\Models;

// warehouse table get data

use Illuminate\Database\Eloquent\Model;

class warehouse_type extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_warehouse_type';
    public $fillable = ['type', 'status'];
}
