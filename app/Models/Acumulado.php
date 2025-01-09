<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acumulado extends Model
{
    use HasFactory;

    protected $table =  'acumulado';

    protected $fillable = [
        'name',
        'local_id',
        'NumPlaca',
        'nombre',
        'entradas',
        'salidas',
        'CEntradas',
        'CSalidas',
        'acumulado',
        'CAcumulado',
        'OrdenPago',
        'factor',
        'PagoManual',
        'HoraActual',
        'EstadoMaquina',
        'comentario',
        'TipoProtocolo',
        'version',
        'e1c',
        'e2c',
        'e5c',
        'e10c',
        'e20c',
        'e50c',
        'e1e',
        'e2e',
        'e5e',
        'e10e',
        'e20e',
        'e50e',
        'e100e',
        'e200e',
        'e500e',
        's1c',
        's2c',
        's5c',
        's10c',
        's20c',
        's50c',
        's1e',
        's2e',
        's5e',
        's10e',
        's20e',
        's50e',
        's100e',
        's200e',
        's500e',
        'c10c',
        'c20c',
        'c50c',
        'c1e',
        'c2e',
    ];

    public function local()
    {
       return $this->belongsTo(Local::class, 'local_id');
    }
}
