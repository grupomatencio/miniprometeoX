<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNumbers extends Model
{
    use HasFactory;

    protected $table =  'serialnumbers';

    protected $fallable = [
        'serial_number', 'local_id'
    ];
}
