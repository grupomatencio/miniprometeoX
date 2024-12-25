<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    protected $fillable = [
        'name',
        'id_zone',
        'ip_address',
        'port',
        'idMachine'
    ];

    public function zone()
    {
       return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }



}
