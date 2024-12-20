<?php

namespace App\Models;

use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_zone',
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
