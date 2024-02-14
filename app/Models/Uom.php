<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_uom';
    public $fillable = ['code', 'description', 'status'];
}
