<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'delegation_id'];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class,'delegation_id');
    }

    public function locals()
    {
        return $this->hasMany(Local::class, 'zone_id');
    }

}
