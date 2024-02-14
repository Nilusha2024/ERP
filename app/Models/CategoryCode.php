<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryCode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_category_code';
    public $fillable = ['code', 'description', 'status'];
}
