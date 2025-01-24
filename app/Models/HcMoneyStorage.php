<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcMoneyStorage extends Model
{
    use HasFactory;

    // Definir la tabla correspondiente
    protected $table = 'hcmoneystorage';

    // Definir los campos que son asignables en masa
    protected $fillable = [
        'local_id',
        'Machine',
        'TypeIsHC',
        'HCName',
        'MoneyIn',
        'MoneyOut',
        'State',
    ];

    // Definir la relación con el modelo Local
    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }
}
