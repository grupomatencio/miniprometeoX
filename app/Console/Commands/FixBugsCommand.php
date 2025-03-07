<?php

namespace App\Console\Commands;

use Dom\Comment;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\Machine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FixBugsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'miniprometeo:fix-bugs-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir errores de los tickets y mandarls a la auxiliar que toca cada ticket en ve de descontarlo de la auxiliar 0 y del total de la máquina de cambio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $conexion = nuevaConexionLocal('ccm');

        // Obtener la última fecha de procesamiento desde el cache
        $lastProcessedDateTime = Cache::get('last_processed_datetime');

        // Si no hay fecha en el cache, usar una fecha de 6 meses atrás
        if (is_null($lastProcessedDateTime)) {
            $lastProcessedDateTime = now()->subMonths(6); // Seis meses atrás
        }

        // Obtener solo los tickets que han sido creados o actualizados después de la última fecha de procesamiento
        $tickets = DB::connection($conexion)
            ->table('tickets')
            ->where('DateTime', '>', $lastProcessedDateTime) // Nuevos tickets
            ->get();

        // Procesar tickets TECNAUSA
        $ticketsTecnausa = $tickets->filter(function ($ticket) {
            return $ticket->Type === 'TECNAUSA';
        });
        $this->TicketsTecnausa($conexion, $ticketsTecnausa);
        // Procesar tickets DATAFONO
        $ticketsDatafono = $tickets->filter(function ($ticket) {
            return $ticket->Type === 'DATAFONO';
        });
        $this->TicketsDatafono($conexion, $ticketsDatafono);

        // Actualizar la última fecha de procesamiento en el cache
        if ($tickets->isNotEmpty()) {
            $lastTicketDateTime = $tickets->max('DateTime'); // Obtener la fecha máxima de los tickets procesados
            Cache::put('last_processed_datetime', $lastTicketDateTime);
        }

        // Log para indicar que el procesamiento se completó
        Log::info("Procesamiento de tickets completado.");
        // Mensaje en la consola
        echo "Procesamiento de tickets completado." . PHP_EOL; // Muestra en la consola
    }



    // Método para corregir fallos de los tickets y sus recargas auxiliares Tecnausa
    public function TicketsTecnausa($conexion, $tickets)
    {
        // Contador de tickets editados
        $editedCount = 0;

        foreach ($tickets as $ticket) {
            // Extraer el alias del campo Comment
            if (preg_match('/Pago Manual:\s*(.+)$/', $ticket->Comment, $matches)) {
                $alias = trim($matches[1]); // Obtenemos el alias limpio

                // Buscar la máquina en la base de datos
                $machine = Machine::where('alias', $alias)->first();

                if ($machine) {
                    Log::info("Alias encontrado: {$alias} - Máquina ID: {$machine->id}");

                    // Guardar los valores anteriores del ticket
                    $oldType = $ticket->Type; // Tipo anterior
                    $oldTypeIsAux = $ticket->TypeIsAux; // TipoIsAux anterior

                    // Actualizar el ticket con los valores de la máquina
                    DB::connection($conexion)->table('tickets')
                        ->where('Id', $ticket->Id)
                        ->update([
                            'Type' => $machine->alias,
                            'TypeIsAux' => $machine->r_auxiliar
                        ]);

                    Log::info("Ticket ID: {$ticket->Id} actualizado con Type: {$machine->alias} y TypeIsAux: {$machine->r_auxiliar}");

                    // Obtener la IP del ordenador
                    $ip = gethostbyname(gethostname());

                    // Calcular el microsegundo
                    $currentTime = Carbon::now();
                    $micro = sprintf("%06d", ($currentTime->micro / 1000)); // Calculamos los microsegundos

                    // Registro en la tabla de logs
                    DB::connection($conexion)->table('logs')->insert([
                        'Type' => 'log miniprometeo',
                        'Text' => "Ticket anterior:\nComment - {$ticket->Comment}\nType - {$oldType}\nTypeIsAux - {$oldTypeIsAux}\n\n" .
                            "Ticket corregido:\nComment - {$ticket->Comment}\nType - {$machine->alias}\nTypeIsAux - {$machine->r_auxiliar}",
                        'Link' => '', // Aquí puedes poner un enlace si es necesario
                        'DateTime' => $currentTime,
                        'DateTimeEx' => $micro, // Usar el valor de microsegundos calculado
                        'IP' => $ip,
                        'User' => 'Miniprometeo', // Usuario fijo
                    ]);
                    // Incrementar el contador de tickets editados
                    $editedCount++;
                } else {
                    Log::warning("Alias no encontrado en la base de datos: {$alias}");
                }
            } else {
                Log::warning("No se pudo extraer el alias del campo Comment: {$ticket->Comment}");
            }
        }

        // Log para indicar que el procesamiento se completó y la cantidad de tickets editados
        Log::info("Procesamiento de tickets TECNAUSA completado. Total de tickets editados: {$editedCount}.");
        // Mensaje en la consola
        echo "Procesamiento de tickets TECNAUSA completado." . PHP_EOL .
            "Total de tickets editados: {$editedCount}." . PHP_EOL; // Muestra en la consola
    }


    // Método para corregir fallos de los tickets y sus recargas auxiliares DATAFONO
    public function TicketsDatafono($conexion, $tickets)
    {
        // Contador de tickets editados
        $editedCount = 0;

        foreach ($tickets as $ticket) {
            // Buscar la máquina en la base de datos utilizando el modelo Machine
            $machine = Machine::where('alias', $ticket->Type)->first();

            if ($machine) {
                // Guardar valores anteriores
                $oldType = $ticket->Type;
                $oldTypeIsAux = $ticket->TypeIsAux;

                // Comparar TypeIsAux del ticket con r_auxiliar de la máquina
                if ($oldTypeIsAux != $machine->r_auxiliar) {
                    // Reconectar a la base de datos y actualizar el ticket por TicketNumber
                    DB::connection($conexion)->table('tickets')
                        ->where('TicketNumber', $ticket->TicketNumber)
                        ->update(['TypeIsAux' => $machine->r_auxiliar]);

                    // Obtener la IP del ordenador
                    $ip = gethostbyname(gethostname());
                    $currentTime = Carbon::now();
                    $micro = sprintf("%06d", ($currentTime->micro / 1000)); // Calculamos los microsegundos

                    // Registrar en la tabla de logs con el mismo formato que TECNAUSA
                    DB::connection($conexion)->table('logs')->insert([
                        'Type' => 'log miniprometeo',
                        'Text' => "Ticket anterior:\n" .
                            "TicketNumber - {$ticket->TicketNumber}\n" .
                            "Comment - {$ticket->Comment}\n" .
                            "Type - {$oldType}\n" .
                            "TypeIsAux - {$oldTypeIsAux}\n\n" .
                            "Ticket corregido:\n" .
                            "TicketNumber - {$ticket->TicketNumber}\n" .
                            "Comment - {$ticket->Comment}\n" .
                            "Type - {$machine->alias}\n" .
                            "TypeIsAux - {$machine->r_auxiliar}",
                        'Link' => '',
                        'DateTime' => $currentTime,
                        'DateTimeEx' => $micro,
                        'IP' => $ip,
                        'User' => 'Miniprometeo',
                    ]);
                    $editedCount++;
                }
            } else {
                Log::warning("No se encontró una máquina con alias: {$ticket->Type} para TicketNumber: {$ticket->TicketNumber}");
            }
        }

        Log::info("Procesamiento de tickets DATAFONO completado.  Total de tickets editados: {$editedCount}.");
        echo "Procesamiento de tickets DATAFONO completado." . PHP_EOL .
            "Total de tickets editados: {$editedCount}." . PHP_EOL;
    }


    // metodo para corregir fallos de los tickets y sus recargas auxiliares Zitro y Bryke
    public function TicketsZitroBryke($conexion, $tickets)
    {

        /*foreach ($tickets as $ticket) {
            Log::info($ticket);
        }*/
    }

    // metodo para corregir fallos de los tickets y sus recargas auxiliares Machines
    public function TicketsMachines($tickets) {}

    // metodo para corregir fallos de los tickets y sus recargas auxiliares Roulette
    public function TicketsRoulette($tickets) {}

    public function AsingTypeAlias($type, $alias){


    }
}
