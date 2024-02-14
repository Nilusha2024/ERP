<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneCheck extends Model
{
    use HasFactory;

    protected $table = 'tbl_zone_check';

    protected $fillable = [
        'location_id',
        'comments',
        'check_zone_manager',
        'zone_user_id',
        'center_user_id',
    ];

}
