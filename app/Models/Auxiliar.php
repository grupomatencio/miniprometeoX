<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auxiliar extends Model
{
    use HasFactory;

    protected $table =  'auxiliares';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id', 'id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id', 'id');
    }

    public function loads()
    {
        return $this->hasMany(Load::class);
    }
}
