<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collect extends Model
{
    use HasFactory;

    // Definir la tabla correspondiente
    protected $table = 'collects';

    // Definir los campos que son asignables en masa
    protected $fillable = [
        'local_id',
        'UserMoney',
        'LocationType',
        'MoneyType',
        'MoneyValue',
        'Quantity',
        'Amount',
        'State',
    ];

    // Definir la relación con el modelo Local
    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }
}
