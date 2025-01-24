<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    // Definir la tabla correspondiente
    protected $table = 'config';

    // Campos asignables en masa
    protected $fillable = [
        'key',        // Clave de configuración (ajustar según tu esquema)
        'value',      // Valor de configuración
        'description' // Descripción opcional del campo (si existe)
    ];

    // Relación con el modelo Local (si aplica)
    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    // Relación con el modelo User (si aplica)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
