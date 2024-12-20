<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function zones()
    {
        return $this->hasMany(Zone::class,'delegation_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'delegations_users');
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }
}
