<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Local;
use App\Models\Acumulado;
use App\Models\Machine;
use Exception;
use Carbon\Carbon;

class PerformAcumuladoSynchronization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perform-acumulado-synchronization';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script que sincroniza los datos de tabla "acumulados" entre ComDataHost y Miniprometeo';

    /**
     * Execute the console command.
     */

    public function handle(): void
    {
        $conexionComdata = nuevaConexionLocal('admin');

        if (!$conexionComdata) {
            Log::error('Error: la conexión con ComData es nula o inválida.');
            return;
        }

        // Obtener los datos de acumulado desde la conexión externa
        try {
            $acumulados = DB::connection($conexionComdata)->table('acumulado')->get();
        } catch (\Exception $e) {
            Log::error('Error leyendo la tabla Acumulados: ' . $e->getMessage());
            return;
        }

        $local = Local::first();
        $machinesPrometeo = Machine::all();

        // Crear un array indexado por alias para búsqueda rápida
        $machinesMap = $machinesPrometeo->keyBy('alias'); // Usamos alias como clave

        Log::info('maquinas acumulados -----' . json_encode($acumulados));
        Log::info('machines en Prometeo: ' . json_encode($machinesMap));

        if ($acumulados->isNotEmpty()) {
            DB::beginTransaction();
            try {
                foreach ($acumulados as $acumulado) {
                    Log::info('Procesando acumulado: ' . json_encode($acumulado));

                    // Buscar machine_id por nombre de acumulado comparado con alias de máquina
                    $machineId = $machinesMap[$acumulado->nombre]->id ?? null;

                    if (!$machineId) {
                        Log::warning('No se encontró una máquina con alias=' . $acumulado->nombre);
                        continue; // Saltar esta iteración si no hay una máquina asociada
                    }

                    $existingRecord = Acumulado::where('nombre', $acumulado->nombre)->first();

                    $data = [
                        'NumPlaca' => $acumulado->NumPlaca,
                        'local_id' => $local->id,
                        'machine_id' => $machineId, // Ahora sí asociamos correctamente
                        'nombre' => $acumulado->nombre,
                        'entradas' => $acumulado->entradas,
                        'salidas' => $acumulado->salidas,
                        'CEntradas' => $acumulado->CEntradas,
                        'CSalidas' => $acumulado->CSalidas,
                        'acumulado' => $acumulado->acumulado,
                        'CAcumulado' => $acumulado->CAcumulado,
                        'OrdenPago' => $acumulado->OrdenPago,
                        'factor' => $acumulado->factor,
                        'PagoManual' => $acumulado->PagoManual,
                        'HoraActual' => $acumulado->HoraActual,
                        'EstadoMaquina' => $acumulado->EstadoMaquina,
                        'comentario' => $acumulado->comentario,
                        'TipoProtocolo' => $acumulado->TipoProtocolo,
                        'version' => $acumulado->version,
                        'e1c' => $acumulado->e1c,
                        'e2c' => $acumulado->e2c,
                        'e5c' => $acumulado->e5c,
                        'e10c' => $acumulado->e10c,
                        'e20c' => $acumulado->e20c,
                        'e50c' => $acumulado->e50c,
                        'e1e' => $acumulado->s1e,
                        'e2e' => $acumulado->s2e,
                        'e5e' => $acumulado->s5e,
                        'e10e' => $acumulado->s10e,
                        'e20e' => $acumulado->s20e,
                        'e50e' => $acumulado->s50e,
                        'e100e' => $acumulado->s100e,
                        'e200e' => $acumulado->s200e,
                        'e500e' => $acumulado->s500e,
                        's1c' => $acumulado->s1c,
                        's2c' => $acumulado->s2c,
                        's5c' => $acumulado->s5c,
                        's10c' => $acumulado->s10c,
                        's20c' => $acumulado->s20c,
                        's50c' => $acumulado->s50c,
                        's1e' => $acumulado->s1e,
                        's2e' => $acumulado->s2e,
                        's5e' => $acumulado->s5e,
                        's10e' => $acumulado->s10e,
                        's20e' => $acumulado->s20e,
                        's50e' => $acumulado->s50e,
                        's100e' => $acumulado->s100e,
                        's200e' => $acumulado->s200e,
                        's500e' => $acumulado->s500e,
                        'c10c' => $acumulado->c10c,
                        'c20c' => $acumulado->c20c,
                        'c50c' => $acumulado->c50c,
                        'c1e' => $acumulado->c1e,
                        'c2e' => $acumulado->c2e,
                        'updated_at' => now(),
                    ];

                    if ($existingRecord) {
                        $existingRecord->update($data);
                        Log::info("Registro actualizado en acumulado: id={$existingRecord->id}, Nombre={$acumulado->nombre}");
                    } else {
                        $data['created_at'] = now();
                        Acumulado::insert($data);
                        Log::info("Nuevo registro insertado en acumulado: Nombre={$acumulado->nombre}");
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error sincronizando acumulado para local_id: ' . $local->id . ' - ' . $e->getMessage());
            }
        } else {
            Log::info('No se recibieron datos de acumulado.');
        }
    }

    /*public function handle(): void
    {
        $conexionComdata = nuevaConexionLocal('admin');

        if (!$conexionComdata) {
            Log::error('Error: la conexión con ComData es nula o inválida.');
            return;
        }
        // Obtener los datos de las tablas para traer los datos
        try {
            $machines = DB::connection($conexionComdata)->table('acumulado')->get();
        } catch (\Exception $e) {
            Log::error('Error de leyendo la tabla Acumulados: ' . $e->getMessage());
        }
        $local = Local::first();
        $machinesPrometeo = Machine::all();
        //dd($machinesPrometeo);
        Log::info('maquinas acumulados -----' .$machines);
        Log::info($machinesPrometeo);

        // Si tenemos respuesta del servidor - comprobamos y guardamos
        if (isset($machines)) {

            // TABLAS PARA PARA INSERETAR DATOS O ACTUALIZARLOS, SEUGUN SI HAY CAMBIOS O NO
            DB::beginTransaction();
            try {
                // INSERT OR UPDATE para la tabla collects
                foreach ($machines as $machine) {

                Log::info(json_encode($machine));


                    log::info($machine->nombre);

                    $existingRecord = Acumulado::where('NumPlaca', $machine -> NumPlaca)
                                    ->where('NumPlaca', $machine->NumPlaca)
                                    ->first();

                    if ($existingRecord) {
                        // Actualizar registro existente
                        $existingRecord
                            ->update([
                                'local_id' => $local->id, // insertar el local_id del local
                                //'machine_id' => $machinesPrometeo->id, // insertar el machine_id de la maquina
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
                            'NumPlaca'=> $machine -> NumPlaca,
                            'local_id' => $local->id,  // Insertar local_id
                            //'machine_id' => $machinesPrometeo->id, // insertar el machine_id de la maquina
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
        } else {  // si NO tenemos respuesta del servidor - DESCONECTAMOS todos machines
            DB::beginTransaction();
            try {
                $machines = DB::connection($conexionComdata)->table('acumulado')->get();
                Log::info('Resultado de la consulta acumulado:', ['machines' => $machines]);
            } catch (\Exception $e) {
                Log::error('Error leyendo la tabla Acumulados: ' . $e->getMessage());
                return; // Salir de la función si hay un error
            }

        }
    }*/
}
