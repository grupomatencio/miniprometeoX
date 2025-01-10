<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table =  'tickets';
    public $timestamps = true;
    use HasFactory;

    public function local()
    {
        return $this->belongsTo(Local::class, 'idMachine');
    }

    public static function totalTickectsSegunTipo($tickets)
    {
        $totalTickets = [];

        foreach ($tickets as $ticket) {
            // Si ya existe una entrada para este tipo de ticket, sumamos el valor
            if (array_key_exists($ticket->Type, $totalTickets)) {
                $totalTickets[$ticket->Type]['valor'] += $ticket->Value;
            } else {
                // Si no existe una entrada para este tipo de ticket, la creamos
                $totalTickets[$ticket->Type] = [
                    'name' => $ticket->Type,
                    'valor' => $ticket->Value
                ];
            }
        }

        // Convertimos el array en formato JSON
        $jsonTotalTickets = json_encode(array_values($totalTickets));

        return $jsonTotalTickets;
    }


}



