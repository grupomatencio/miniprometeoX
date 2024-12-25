<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachinePrometeo extends Model
{
    use HasFactory;

    protected $table =  'machines1';

    protected $fallable = [
        'name', 'alias', 'local_id', 'bar_id', 'delegation_id', 'identificador'
    ];
}
