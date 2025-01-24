<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    // Definir la tabla correspondiente
    protected $table = 'logs';

    // Campos asignables en masa
    protected $fillable = [
        'local_id',
        'Type',
        'Text',
        'Link',
        'DateTime',
        'DateTimeEx',
        'IP',
        'User',
    ];

    // Relación con el modelo Local
    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }
}
