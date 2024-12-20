<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Load extends Model
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'updated_at',
        'date_recovered', // Añade esta línea para tratar `date_recovered` como una fecha
    ];

    // Si prefieres usar `$fillable`
    protected $fillable = [
        'Number',
        'Quantity',
        'Created_for',
        'Closed_for',
        'Partial_quantity',
        'Irrecoverable',
        'Initial',
        'State',
        'date_recovered',
        'machine_id',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'Created_for');
    }

    public function userClosed()
    {
        return $this->belongsTo(User::class, 'Closed_for');
    }
}
