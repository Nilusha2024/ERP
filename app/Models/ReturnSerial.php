<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ReturnSerial extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_return_serial';

    public $fillable = ['return_id','return_detail_id','item_id', 'serial_no',  'created_by', 'status'];

   
}
