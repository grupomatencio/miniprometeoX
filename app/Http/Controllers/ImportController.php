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
                                -> orWhere('type',null)
                                -> get();
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

                $machines_prometeo =$connection->table('machines')
                     -> where('local_id',$local[0] -> id)
                     //-> where('parent',)
                     -> get ();

            } catch (\Exception $exception) {
                Log::info($exception);
                $message = "No hay connexión";
            }


            $diferencia = []; // Deferncia entre $machines & $machines_prometeo

            if ($machines_prometeo -> isNotEmpty()) {
                $diferencia = $this -> comparar($machines, $machines_prometeo);
            }

            // Filtramos machines para excluir 'roulette y parent'

            $machines_prometeo_filtered = collect($machines_prometeo) -> filter(function($item) {
                return $item->type == 'single' || $item->type === null;
                });


            return view("import.index", ["machines" => $machines,
                                                     "machines_prometeo" => $machines_prometeo_filtered,
                                                     "importBD" => $importBD,
                                                     "message" => $message,
                                                     "diferencia" => $diferencia,
                                                    ]);
        } catch (\Exception $e) {
             dd ($e->getMessage());
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function store()
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
            return redirect()->back()->with("error", $e->getMessage());
        }

    }

    // function para comparar arrays de machines
    // @return $diff - array con diferencia
    private function comparar ($machines, $machines_prometeo) {
        $identificadoresMachine = $machines -> pluck ('identificador');
        $identificadoresMachinePrometeo = $machines_prometeo -> pluck ('identificador');

        $diferencia = $identificadoresMachine -> diff($identificadoresMachinePrometeo);

        $diff = $diferencia -> values() ->toArray ();

        return $diff;
    }

}
