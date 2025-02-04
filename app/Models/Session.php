<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions';

    protected $primaryKey = 'id'; // Establecer 'id' como la clave primaria

    public $incrementing = false; // Desactivar el autoincremento ya que 'id' es de tipo string

    protected $fillable = [
        'id',                // Identificador de la sesión
        'user_id',          // Clave foránea que puede ser nula
        'ip_address',       // Dirección IP del usuario
        'user_agent',       // Agente de usuario
        'payload',          // Datos de la sesión
        'last_activity',    // Última actividad
    ];

    public function local()
    {
        return $this->hasMany(Local::class, 'local_id', 'id');
    }
}
