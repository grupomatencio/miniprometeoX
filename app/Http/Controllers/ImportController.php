<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\MachinePrometeo;
use App\Models\Local;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{

    public function index()
    {

        try {

            $machines = Machine::where('type', 'single')
                ->orWhere('type', null)
                ->get();
            $machines_prometeo = collect();
            $importBD = false;        // Variables para resultado de importacón
            $message = "";              // Mensaje de informacion de servicio
            $local = Local::all();

            // Si no hay locales - volveremos un error
            if (count($local) !== 1) {
                return redirect()->back()->with("errorConfiguracion", "No hay configuración del sistema");
            }

            // Obtener datos de machines de prometeo
            try {
                $connection = DB::connection('remote_prometeo_test');

                $machines_prometeo = $connection->table('machines')
                    ->where('local_id', $local[0]->id)
                    //-> where('parent',)
                    ->get();
            } catch (\Exception $exception) {
                Log::info($exception);
                $message = "No hay connexión";
            }


            $diferencia = []; // Deferncia entre $machines & $machines_prometeo
            $faltantes = [];

            if ($machines_prometeo->isNotEmpty()) {
                $diferencias = $this->comparar($machines, $machines_prometeo);
                $diferencia = $diferencias['diferencia']; // Máquinas en MiniPrometeo que no están en Prometeo
                $faltantes = $diferencias['faltantes'];  // Máquinas en Prometeo que no están en MiniPrometeo
            }


            // Filtramos machines para excluir 'roulette y parent'

            $machines_prometeo_filtered = collect($machines_prometeo)->filter(function ($item) {
                return $item->type == 'single' || $item->type === null;
            });
            //dd($faltantes);

            return view("import.index", [
                "machines" => $machines,
                "machines_prometeo" => $machines_prometeo_filtered,
                "importBD" => $importBD,
                "message" => $message,
                "diferencia" => $diferencia,
                "faltantes" => $faltantes
            ]);
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /*public function store()
    {

        try {
            $machines = Machine::all();
            $machines_prometeo = collect();
            $importBD = false;        // Variables para resultado de importacón
            $message = "";              // Mensaje de informacion de servicio
            $local = Local::all();

            if (count($local) !== 1) {
                return redirect()->back()->with("errorConfiguracion", "No hay configuración de sistema");
            }

            // Obtener datos de machines de prometeo
            try {
                $connection = DB::connection('remote_prometeo_test');

                $machines_prometeo =$connection->table('machines')
                     -> where('local_id',$local[0] -> id)
                     -> get ();


            } catch (\Exception $exception) {
                Log::info($exception);
                $message = "No hay connexión";
            }


            $machines_prometeo_array = $machines_prometeo->toArray();

            // Eliminar tabla machines de miniprometeo y recargar de nuevo
            Machine::truncate();

            foreach ($machines_prometeo_array as $machine) {

                try {
                    $newMachine = new Machine;
                    $newMachine -> name = $machine -> name;
                    $newMachine -> alias = $machine -> alias;
                    $newMachine -> local_id = $machine -> local_id;
                    $newMachine -> bar_id = $machine -> bar_id;
                    $newMachine -> delegation_id = $machine -> delegation_id;
                    $newMachine -> identificador = $machine -> identificador;
                    $newMachine -> type = $machine -> type;
                    $newMachine ->id = $machine -> id;
                    $newMachine -> parent_id = $machine -> parent_id;
                    $newMachine -> r_auxiliar = $machine -> r_auxiliar;

                    $newMachine -> save();

                    Log::info($newMachine);
                } catch (\Exception $e) {
                    Log::info($e -> getMessage());
                }
            }

            $machines = Machine::where('type', 'single')
                                -> orWhere('type',null)
                                -> get();

            $importBD = true;        // habia importacion - true
            $message = "La importacion de datos se ha realizado correctamente.";              // Mensaje de informacion de servicio
            $diferencia = [];

            // Filtramos machines para excluir 'roulette y parent'

            $machines_prometeo_filtered = collect($machines_prometeo) -> filter(function($item) {
                return $item->type == 'single' || $item->type === null;
                });

            return view("import.index", ["machines" => $machines,
                              "machines_prometeo" => $machines_prometeo_filtered,
                               "importBD" => $importBD,
                              "message" => $message,
                              "diferencia" => $diferencia]);
        } catch (\Exception $e) {

            Log::info(message: $e -> getMessage());

            return redirect()->back()->with("error", $e->getMessage());
        }

    }*/

    // function para comparar arrays de machines
    // @return $diff - array con diferencia
    /*private function comparar ($machines, $machines_prometeo) {
        $identificadoresMachine = $machines -> pluck ('identificador');
        $identificadoresMachinePrometeo = $machines_prometeo -> pluck ('identificador');

        $diferencia = $identificadoresMachine -> diff($identificadoresMachinePrometeo);

        $diff = $diferencia -> values() ->toArray ();

        return $diff;
    }*/

    private function comparar($machines, $machines_prometeo)
    {
        $identificadoresMachine = $machines->pluck('identificador');
        $identificadoresMachinePrometeo = $machines_prometeo->pluck('identificador');

        // Máquinas que están en MiniPrometeo pero no en Prometeo
        $diferencia = $identificadoresMachine->diff($identificadoresMachinePrometeo);

        // Máquinas que están en Prometeo pero no en MiniPrometeo
        $faltantes = $identificadoresMachinePrometeo->diff($identificadoresMachine);

        // Convertir faltantes a alias
        $faltantes = $faltantes->map(fn($id) => $machinesPrometeoMap[$id] ?? $id)->values()->toArray();

        return [
            'diferencia' => $diferencia->values()->toArray(), // Sigue devolviendo identificadores
            'faltantes' => $faltantes, // Ahora devuelve alias en lugar de identificadores
        ];
    }


    public function store()
    {
        try {
            $importBD = false;
            $message = "";
            $local = Local::all();

            if ($local->count() !== 1) {
                return redirect()->back()->with("errorConfiguracion", "No hay configuración de sistema");
            }

            try {
                $connection = DB::connection('remote_prometeo_test');

                $machines_prometeo = $connection->table('machines')
                    ->where('local_id', $local[0]->id)
                    ->get();
            } catch (\Exception $exception) {
                Log::info($exception);
                return redirect()->back()->with("errorConfiguracion", "No hay conexión con Prometeo");
            }

            // Obtener los identificadores de las máquinas de Prometeo
            $machines_prometeo_ids = $machines_prometeo->pluck('identificador')->toArray();

            // Eliminar máquinas que no están en Prometeo
            Machine::whereNotIn('identificador', $machines_prometeo_ids)->delete();

            // Insertar o actualizar máquinas de Prometeo
            foreach ($machines_prometeo as $machine) {
                Machine::updateOrCreate(
                    ['identificador' => $machine->identificador],  // Clave única para encontrar la máquina
                    [
                        'id' => $machine->id,
                        'name' => $machine->name,
                        'alias' => $machine->alias,
                        'local_id' => $machine->local_id,
                        'bar_id' => $machine->bar_id,
                        'delegation_id' => $machine->delegation_id,
                        'type' => $machine->type,
                        'parent_id' => $machine->parent_id,
                        'r_auxiliar' => $machine->r_auxiliar
                    ]
                );
            }

            $machines = Machine::where('type', 'single')
                ->orWhereNull('type')
                ->get();

            $importBD = true;
            $message = "La importación de datos se ha realizado correctamente.";

            // Filtrar máquinas de Prometeo que no son 'roulette' ni 'parent'
            $machines_prometeo_filtered = $machines_prometeo->filter(function ($item) {
                return $item->type == 'single' || $item->type === null;
            });

            return view("import.index", [
                "machines" => $machines,
                "machines_prometeo" => $machines_prometeo_filtered,
                "importBD" => $importBD,
                "message" => $message,
                "diferencia" => []
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
