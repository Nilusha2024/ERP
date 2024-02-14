<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    // table
    protected $table = 'tbl_vendor';

    // in regards to mass assignment rule
    protected $fillable = [
        'name',
        'status',
    ];
}
