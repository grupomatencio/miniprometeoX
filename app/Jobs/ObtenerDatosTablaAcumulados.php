<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Local;
use App\Models\Acumulado;
use Exception;
use Carbon\Carbon;

use function Pest\version;

class ObtenerDatosTablaAcumulados implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $conexionConTicketServer = nuevaConexionLocal('admin');

        // Obtener los datos de las tablas para traer los datos
        try {
            $machines = DB::connection($conexionConTicketServer)->table('acumulado')->get();
        } catch (\Exception $e) {
            Log::error('Error de leyendo la tabla Acumulados: ' . $e->getMessage());
        }
        $local = Local::first();

        // TABLAS PARA PARA INSERETAR DATOS O ACTUALIZARLOS, SEUGUN SI HAY CAMBIOS O NO
        DB::beginTransaction();

        try {


            // INSERT OR UPDATE para la tabla collects
            foreach ($machines as $machine) {

                log::info($machine->nombre);

                $existingRecord = Acumulado::where('NumPlaca', $machine -> NumPlaca)
                                ->where('nombre', $machine->nombre)
                                ->first();

                if ($existingRecord) {
                    // Actualizar registro existente
                    $existingRecord
                        ->update([
                            'local_id' => $local->id,
                            'entradas'=> $machine->entradas,
                            'salidas'=> $machine->salidas,
                            'CEntradas'=> $machine->CEntradas,
                            'CSalidas'=> $machine->CSalidas,
                            'acumulado'=> $machine->acumulado,
                            'CAcumulado'=> $machine->CAcumulado,
                            'OrdenPago'=> $machine->OrdenPago,
                            'factor'=> $machine->factor,
                            'PagoManual'=> $machine->PagoManual,
                            'HoraActual'=> $machine->HoraActual,
                            'EstadoMaquina'=> $machine->EstadoMaquina,
                            'comentario'=> $machine->comentario,
                            'TipoProtocolo'=> $machine->TipoProtocolo,
                            'version' => $machine->version,
                            'e1c'=> $machine->e1c,
                            'e2c'=> $machine->e2c,
                            'e5c'=> $machine->e5c,
                            'e10c'=> $machine->e10c,
                            'e20c'=> $machine->e20c,
                            'e50c'=> $machine->e50c,
                            'e1e'=> $machine->s1e,
                            'e2e'=> $machine->s2e,
                            'e5e'=> $machine->s5e,
                            'e10e'=> $machine->s10e,
                            'e20e'=> $machine->s20e,
                            'e50e'=> $machine->s50e,
                            'e100e'=> $machine->s100e,
                            'e200e'=> $machine->s200e,
                            'e500e'=> $machine->s500e,
                            's1c'=> $machine->s1c,
                            's2c'=> $machine->s2c,
                            's5c'=> $machine->s5c,
                            's10c'=> $machine->s10c,
                            's20c'=> $machine->s20c,
                            's50c'=> $machine->s50c,
                            's1e'=> $machine->s1e,
                            's2e'=> $machine->s2e,
                            's5e'=> $machine->s5e,
                            's10e'=> $machine->s10e,
                            's20e'=> $machine->s20e,
                            's50e'=> $machine->s50e,
                            's100e'=> $machine->s100e,
                            's200e'=> $machine->s200e,
                            's500e' => $machine->s500e,
                            'c10c'=> $machine->c10c,
                            'c20c'=> $machine->c20c,
                            'c50c'=> $machine->c50c,
                            'c1e'=> $machine->c1e,
                            'c2e'=> $machine->c2e,
                            'updated_at' => now(),
                        ]);

                    Log::info('Registro actualizado en acumulado: id=' . $existingRecord->id . ', local_id=' . $local->id . ', NumPlaca=' . $existingRecord->NumPlaca);
                } else {

                    // Insertar nuevo registro
                    log::info('No exist');
                    Acumulado::insert([
                        'local_id' => $local->id,  // Insertar local_id
                        'NumPlaca'=> $machine -> NumPlaca,
                        'nombre'=> $machine->nombre,
                        'entradas'=> $machine->entradas,
                        'salidas'=> $machine->salidas,
                        'CEntradas'=> $machine->CEntradas,
                        'CSalidas'=> $machine->CSalidas,
                        'acumulado'=> $machine->acumulado,
                        'CAcumulado'=> $machine->CAcumulado,
                        'OrdenPago'=> $machine->OrdenPago,
                        'factor'=> $machine->factor,
                        'PagoManual'=> $machine->PagoManual,
                        'HoraActual'=> $machine->HoraActual,
                        'EstadoMaquina'=> $machine->EstadoMaquina,
                        'comentario'=> $machine->comentario,
                        'TipoProtocolo'=> $machine->TipoProtocolo,
                        'version' => $machine->version,
                        'e1c'=> $machine->e1c,
                        'e2c'=> $machine->e2c,
                        'e5c'=> $machine->e5c,
                        'e10c'=> $machine->e10c,
                        'e20c'=> $machine->e20c,
                        'e50c'=> $machine->e50c,
                        'e1e'=> $machine->s1e,
                        'e2e'=> $machine->s2e,
                        'e5e'=> $machine->s5e,
                        'e10e'=> $machine->s10e,
                        'e20e'=> $machine->s20e,
                        'e50e'=> $machine->s50e,
                        'e100e'=> $machine->s100e,
                        'e200e'=> $machine->s200e,
                        'e500e'=> $machine->s500e,
                        's1c'=> $machine->s1c,
                        's2c'=> $machine->s2c,
                        's5c'=> $machine->s5c,
                        's10c'=> $machine->s10c,
                        's20c'=> $machine->s20c,
                        's50c'=> $machine->s50c,
                        's1e'=> $machine->s1e,
                        's2e'=> $machine->s2e,
                        's5e'=> $machine->s5e,
                        's10e'=> $machine->s10e,
                        's20e'=> $machine->s20e,
                        's50e'=> $machine->s50e,
                        's100e'=> $machine->s100e,
                        's200e'=> $machine->s200e,
                        's500e'=> $machine->s500e,
                        'c10c'=> $machine->c10c,
                        'c20c'=> $machine->c20c,
                        'c50c'=> $machine->c50c,
                        'c1e'=> $machine->c1e,
                        'c2e'=> $machine->c2e,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('Registro actualizado en acumulado: local_id=' . $local->id . ', NumPlaca=' . $machine->NumPlaca);
                }

            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sincronizando acumulado para local_id: ' . $local->id . ' - ' . $e->getMessage());
        }

    }
}
