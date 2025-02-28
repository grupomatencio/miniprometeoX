<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Acumulado;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfigurationAccountantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Conexión con la base de datos externa
            $conexionComdata = nuevaConexionLocal('admin');

            if (!$conexionComdata) {
                //Log::error('Error: la conexión con ComData es nula o inválida.');
                return;
            }

            // Obtener todos los acumulados desde ComData
            try {
                $acumulados = DB::connection($conexionComdata)
                    ->table('acumulado')
                    ->orderBy('NumPlaca', 'ASC')
                    ->get();

                // Extraer valores únicos de NumPlaca desde ComData
                $numPlacas = $acumulados->pluck('NumPlaca')->unique()->values();
            } catch (\Exception $e) {
                //Log::error('Error leyendo la tabla Acumulados: ' . $e->getMessage());
                return;
            }

            // Obtener máquinas
            $machines = Machine::where('type', 'single')
                ->orWhere('type', null)
                ->get();

            // Obtener los numPlacas ya asignados en nuestra base de datos
            $acumuladosLocales = Acumulado::pluck('NumPlaca', 'machine_id');

            return view("configurationAccountants.index", compact("machines", "acumulados", "numPlacas", "acumuladosLocales"));
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /*public function store(Request $request)
    {
        //dd($request->all());

        try {
            // Validar la solicitud
            $request->validate([
                'machine_id' => 'required|exists:machines,id',
                'alias' => 'required|array',
                'numPlaca' => 'required|array',
            ]);

            $machineId = $request->machine_id;
            $numPlaca = collect($request->numPlaca)->first();
            $alias = collect($request->alias)->first();
            $local = Local::first();

            // Si `NumPlaca` es 0 o vacío, eliminar todas las asociaciones de esa máquina y salir
            if (empty($numPlaca) || $numPlaca == "0") {
                Acumulado::where('machine_id', $machineId)->delete();

                session()->flash('success', 'La máquina ha sido desvinculada de sus antiguos contadores.');
                return redirect()->back();
            }

            // **Eliminar cualquier otra placa asociada a esta máquina antes de guardar la nueva**
            Acumulado::where('machine_id', $machineId)->delete();

            // Verificar si ya existe un registro con el mismo `NumPlaca`
            $existe = Acumulado::where('NumPlaca', $numPlaca)->exists();
            if ($existe) {
                session()->flash('error', "El número de placa $numPlaca ya está asociado a otra máquina.");
                return redirect()->back();
            }

            // Conectar con la base de datos externa
            $conexionComdata = nuevaConexionLocal('admin');
            if (!$conexionComdata) {
                session()->flash('error', 'Error de conexión con ComData.');
                return redirect()->back();
            }

            $datosAcumulado = DB::connection($conexionComdata)
                ->table('acumulado')
                ->where('NumPlaca', $numPlaca)
                ->first();

            if (!$datosAcumulado) {
                session()->flash('error', 'No se encontraron datos en la tabla acumulado para este NumPlaca.');
                return redirect()->back();
            }

            // Crear el nuevo registro en nuestra base de datos
            Acumulado::create([
                'NumPlaca' => $numPlaca,
                'local_id' => $local->id,
                'machine_id' => $machineId,
                'nombre' => $alias,
                'entradas' => $datosAcumulado->entradas ?? 0,
                'salidas' => $datosAcumulado->salidas ?? 0,
                'CEntradas' => $datosAcumulado->CEntradas ?? 0,
                'CSalidas' => $datosAcumulado->CSalidas ?? 0,
                'acumulado' => $datosAcumulado->acumulado ?? 0,
                'CAcumulado' => $datosAcumulado->CAcumulado ?? 0,
                'OrdenPago' => $datosAcumulado->OrdenPago ?? 0,
                'factor' => $datosAcumulado->factor ?? 1,
                'PagoManual' => $datosAcumulado->PagoManual ?? 0,
                'HoraActual' => $datosAcumulado->HoraActual ?? now(),
                'EstadoMaquina' => $datosAcumulado->EstadoMaquina ?? 'Desconocido',
                'comentario' => $datosAcumulado->comentario ?? null,
                'TipoProtocolo' => $datosAcumulado->TipoProtocolo ?? null,
                'version' => $datosAcumulado->version ?? null,
                'e1c' => $datosAcumulado->e1c,
                'e2c' => $datosAcumulado->e2c,
                'e5c' => $datosAcumulado->e5c,
                'e10c' => $datosAcumulado->e10c,
                'e20c' => $datosAcumulado->e20c,
                'e50c' => $datosAcumulado->e50c,
                'e1e' => $datosAcumulado->s1e,
                'e2e' => $datosAcumulado->s2e,
                'e5e' => $datosAcumulado->s5e,
                'e10e' => $datosAcumulado->s10e,
                'e20e' => $datosAcumulado->s20e,
                'e50e' => $datosAcumulado->s50e,
                'e100e' => $datosAcumulado->s100e,
                'e200e' => $datosAcumulado->s200e,
                'e500e' => $datosAcumulado->s500e,
                's1c' => $datosAcumulado->s1c,
                's2c' => $datosAcumulado->s2c,
                's5c' => $datosAcumulado->s5c,
                's10c' => $datosAcumulado->s10c,
                's20c' => $datosAcumulado->s20c,
                's50c' => $datosAcumulado->s50c,
                's1e' => $datosAcumulado->s1e,
                's2e' => $datosAcumulado->s2e,
                's5e' => $datosAcumulado->s5e,
                's10e' => $datosAcumulado->s10e,
                's20e' => $datosAcumulado->s20e,
                's50e' => $datosAcumulado->s50e,
                's100e' => $datosAcumulado->s100e,
                's200e' => $datosAcumulado->s200e,
                's500e' => $datosAcumulado->s500e,
                'c10c' => $datosAcumulado->c10c,
                'c20c' => $datosAcumulado->c20c,
                'c50c' => $datosAcumulado->c50c,
                'c1e' => $datosAcumulado->c1e,
                'c2e' => $datosAcumulado->c2e,
                'updated_at' => now(),
            ]);

            // Mensaje de éxito
            session()->flash('success', 'Placa asociada y creada exitosamente.');
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
            return redirect()->back();
        }
    }*/

    /*public function store(Request $request)
    {
        try {
            // Obtener ID de la máquina desde el request
            $id_machine = (int) $request->machine_id;

            // Obtener el número de placa desde el request
            $NumPlaca = $request->numPlaca[$id_machine] ?? null;
            if (!$NumPlaca) {
                return back()->with('error', 'No se proporcionó un número de placa.');
            }

            // Conectar a la BD externa
            $conexion = nuevaConexionLocal('admin');

            // Verificar si NumPlaca existe en la tabla acumulado de la BD externa
            $acumuladoExterno = DB::connection($conexion)
                ->table('acumulado')
                ->where('NumPlaca', $NumPlaca)
                ->first();

            if (!$acumuladoExterno) {
                Log::warning("⚠ No se encontró NumPlaca en la BD externa", ['NumPlaca' => $NumPlaca]);
                return redirect()->route('configurationAccountants.index', $request->delegation_id)
                    ->with('error', 'No se encontró la máquina en la tabla acumulado de la BD externa.');
            }

            // Ejecutar la transacción
            DB::transaction(function () use ($id_machine, $request) {
                // Obtener la máquina desde la BD local
                $machine = Machine::find($id_machine);
                if (!$machine) {
                    throw new \Exception("La máquina con ID $id_machine no existe.");
                }

                // Llamar a sendAnularPM con los valores correctos
                $this->sendAnularPM(
                    $id_machine,
                    $request->r_auxiliar[$id_machine] ?? $machine->r_auxiliar ?? null,
                    $request->AnularPM[$id_machine] ?? null
                );
            });

            return redirect()->route('configurationAccountants.index', $request->delegation_id)
                ->with('success', 'Máquina actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("❌ Error al actualizar la máquina", ['error' => $e->getMessage()]);
            return redirect()->route('configurationAccountants.index', $request->delegation_id)
                ->with('error', 'Error al actualizar la máquina: ' . $e->getMessage());
        }



        try {
            // Validar la solicitud
            $request->validate([
                'machine_id' => 'required|exists:machines,id',
                'alias' => 'required|array',
                'numPlaca' => 'required|array',
            ]);

            $machineId = $request->machine_id;
            $numPlaca = collect($request->numPlaca)->first();
            $alias = collect($request->alias)->first();
            $local = Local::first();

            // Si `NumPlaca` es 0 o vacío, eliminar todas las asociaciones de esa máquina y salir
            if (empty($numPlaca) || $numPlaca == "0") {
                Acumulado::where('machine_id', $machineId)->delete();

                session()->flash('success', 'La máquina ha sido desvinculada de sus antiguos contadores.');
                return redirect()->back();
            }

            // **Eliminar cualquier otra placa asociada a esta máquina antes de guardar la nueva**
            Acumulado::where('machine_id', $machineId)->delete();

            // Verificar si ya existe un registro con el mismo `NumPlaca`
            $existe = Acumulado::where('NumPlaca', $numPlaca)->exists();
            if ($existe) {
                session()->flash('error', "El número de placa $numPlaca ya está asociado a otra máquina.");
                return redirect()->back();
            }

            // Conectar con la base de datos externa
            $conexionComdata = nuevaConexionLocal('admin');
            if (!$conexionComdata) {
                session()->flash('error', 'Error de conexión con ComData.');
                return redirect()->back();
            }

            $datosAcumulado = DB::connection($conexionComdata)
                ->table('acumulado')
                ->where('NumPlaca', $numPlaca)
                ->first();

            if (!$datosAcumulado) {
                session()->flash('error', 'No se encontraron datos en la tabla acumulado para este NumPlaca.');
                return redirect()->back();
            }

            // Crear el nuevo registro en nuestra base de datos
            Acumulado::create([
                'NumPlaca' => $numPlaca,
                'local_id' => $local->id,
                'machine_id' => $machineId,
                'nombre' => $alias,
                'entradas' => $datosAcumulado->entradas ?? 0,
                'salidas' => $datosAcumulado->salidas ?? 0,
                'CEntradas' => $datosAcumulado->CEntradas ?? 0,
                'CSalidas' => $datosAcumulado->CSalidas ?? 0,
                'acumulado' => $datosAcumulado->acumulado ?? 0,
                'CAcumulado' => $datosAcumulado->CAcumulado ?? 0,
                'OrdenPago' => $datosAcumulado->OrdenPago ?? 0,
                'factor' => $datosAcumulado->factor ?? 1,
                'PagoManual' => $datosAcumulado->PagoManual ?? 0,
                'HoraActual' => $datosAcumulado->HoraActual ?? now(),
                'EstadoMaquina' => $datosAcumulado->EstadoMaquina ?? 'Desconocido',
                'comentario' => $datosAcumulado->comentario ?? null,
                'TipoProtocolo' => $datosAcumulado->TipoProtocolo ?? null,
                'version' => $datosAcumulado->version ?? null,
                'e1c' => $datosAcumulado->e1c,
                'e2c' => $datosAcumulado->e2c,
                'e5c' => $datosAcumulado->e5c,
                'e10c' => $datosAcumulado->e10c,
                'e20c' => $datosAcumulado->e20c,
                'e50c' => $datosAcumulado->e50c,
                'e1e' => $datosAcumulado->s1e,
                'e2e' => $datosAcumulado->s2e,
                'e5e' => $datosAcumulado->s5e,
                'e10e' => $datosAcumulado->s10e,
                'e20e' => $datosAcumulado->s20e,
                'e50e' => $datosAcumulado->s50e,
                'e100e' => $datosAcumulado->s100e,
                'e200e' => $datosAcumulado->s200e,
                'e500e' => $datosAcumulado->s500e,
                's1c' => $datosAcumulado->s1c,
                's2c' => $datosAcumulado->s2c,
                's5c' => $datosAcumulado->s5c,
                's10c' => $datosAcumulado->s10c,
                's20c' => $datosAcumulado->s20c,
                's50c' => $datosAcumulado->s50c,
                's1e' => $datosAcumulado->s1e,
                's2e' => $datosAcumulado->s2e,
                's5e' => $datosAcumulado->s5e,
                's10e' => $datosAcumulado->s10e,
                's20e' => $datosAcumulado->s20e,
                's50e' => $datosAcumulado->s50e,
                's100e' => $datosAcumulado->s100e,
                's200e' => $datosAcumulado->s200e,
                's500e' => $datosAcumulado->s500e,
                'c10c' => $datosAcumulado->c10c,
                'c20c' => $datosAcumulado->c20c,
                'c50c' => $datosAcumulado->c50c,
                'c1e' => $datosAcumulado->c1e,
                'c2e' => $datosAcumulado->c2e,
                'updated_at' => now(),
            ]);

            // Mensaje de éxito
            session()->flash('success', 'Placa asociada y creada exitosamente.');
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
            return redirect()->back();
        }
    }*/

    public function store(Request $request)
    {
        try {
            // Obtener ID de la máquina desde el request
            $id_machine = (int) $request->machine_id;

            // Obtener el número de placa desde el request
            $NumPlaca = $request->numPlaca[$id_machine] ?? null;
            if (!$NumPlaca) {
                return back()->with('error', 'No se proporcionó un número de placa.');
            }

            // Conectar a la BD externa
            $conexion = nuevaConexionLocal('admin');

            // Verificar si NumPlaca existe en la tabla acumulado de la BD externa
            $acumuladoExterno = DB::connection($conexion)
                ->table('acumulado')
                ->where('NumPlaca', $NumPlaca)
                ->first();

            if (!$acumuladoExterno) {
                Log::warning("⚠ No se encontró NumPlaca en la BD externa", ['NumPlaca' => $NumPlaca]);
                return redirect()->route('configurationAccountants.index', $request->delegation_id)
                    ->with('error', 'No se encontró la máquina en la tabla acumulado de la BD externa.');
            }

            // Ejecutar la transacción
            DB::transaction(function () use ($id_machine, $request, $NumPlaca, $acumuladoExterno) {
                // Obtener la máquina desde la BD local
                $machine = Machine::find($id_machine);
                if (!$machine) {
                    return back()->with('error', 'La máquina no existe');
                }

                // Llamar a sendAnularPM con los valores correctos
                $this->sendAnularPM(
                    $id_machine,
                    $request->r_auxiliar[$id_machine] ?? $machine->r_auxiliar ?? null,
                    $request->AnularPM[$id_machine] ?? null
                );

                // Insertar o actualizar el registro en la BD local
                Acumulado::updateOrCreate(
                    ['NumPlaca' => $NumPlaca, 'local_id' => $machine->local_id], // Condiciones de búsqueda
                    [
                        'machine_id' => $id_machine,
                        'nombre' => $request->nombre ?? null,
                        'entradas' => $acumuladoExterno->entradas ?? 0,
                        'salidas' => $acumuladoExterno->salidas ?? 0,
                        'CEntradas' => $acumuladoExterno->CEntradas ?? 0,
                        'CSalidas' => $acumuladoExterno->CSalidas ?? 0,
                        'acumulado' => $acumuladoExterno->acumulado ?? 0,
                        'CAcumulado' => $acumuladoExterno->CAcumulado ?? 0,
                        'OrdenPago' => $acumuladoExterno->OrdenPago ?? 0,
                        'factor' => $acumuladoExterno->factor ?? 1,
                        'PagoManual' => $acumuladoExterno->PagoManual ?? 0,
                        'HoraActual' => $acumuladoExterno->HoraActual ?? now(),
                        'EstadoMaquina' => $acumuladoExterno->EstadoMaquina ?? 'Desconocido',
                        'comentario' => $acumuladoExterno->comentario ?? null,
                        'TipoProtocolo' => $acumuladoExterno->TipoProtocolo ?? null,
                        'version' => $acumuladoExterno->version ?? null,
                        'e1c' => $acumuladoExterno->e1c,
                        'e2c' => $acumuladoExterno->e2c,
                        'e5c' => $acumuladoExterno->e5c,
                        'e10c' => $acumuladoExterno->e10c,
                        'e20c' => $acumuladoExterno->e20c,
                        'e50c' => $acumuladoExterno->e50c,
                        'e1e' => $acumuladoExterno->s1e,
                        'e2e' => $acumuladoExterno->s2e,
                        'e5e' => $acumuladoExterno->s5e,
                        'e10e' => $acumuladoExterno->s10e,
                        'e20e' => $acumuladoExterno->s20e,
                        'e50e' => $acumuladoExterno->s50e,
                        'e100e' => $acumuladoExterno->s100e,
                        'e200e' => $acumuladoExterno->s200e,
                        'e500e' => $acumuladoExterno->s500e,
                        's1c' => $acumuladoExterno->s1c,
                        's2c' => $acumuladoExterno->s2c,
                        's5c' => $acumuladoExterno->s5c,
                        's10c' => $acumuladoExterno->s10c,
                        's20c' => $acumuladoExterno->s20c,
                        's50c' => $acumuladoExterno->s50c,
                        's1e' => $acumuladoExterno->s1e,
                        's2e' => $acumuladoExterno->s2e,
                        's5e' => $acumuladoExterno->s5e,
                        's10e' => $acumuladoExterno->s10e,
                        's20e' => $acumuladoExterno->s20e,
                        's50e' => $acumuladoExterno->s50e,
                        's100e' => $acumuladoExterno->s100e,
                        's200e' => $acumuladoExterno->s200e,
                        's500e' => $acumuladoExterno->s500e,
                        'c10c' => $acumuladoExterno->c10c,
                        'c20c' => $acumuladoExterno->c20c,
                        'c50c' => $acumuladoExterno->c50c,
                        'c1e' => $acumuladoExterno->c1e,
                        'c2e' => $acumuladoExterno->c2e,
                        'updated_at' => now()
                    ]
                );
            });

            return redirect()->route('configurationAccountants.index', $request->delegation_id)
                ->with('success', 'Máquina actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("❌ Error al actualizar la máquina", ['error' => $e->getMessage()]);
            return redirect()->route('configurationAccountants.index', $request->delegation_id)
                ->with('error', 'Error al actualizar la máquina: ' . $e->getMessage());
        }
    }



    public function storeAll(Request $request)
    {
        //Log::info($request->all());

        try {
            // Validar la solicitud
            $request->validate([
                'machines' => 'required|array',
                'machines.*.machine_id' => 'required|exists:machines,id',
                'machines.*.alias' => 'required',
                'machines.*.numPlaca' => 'required',
            ]);

            $local = Local::first();
            $conexionComdata = nuevaConexionLocal('admin');
            if (!$conexionComdata) {
                return response()->json(['success' => false, 'message' => 'Error de conexión con ComData.'], 500);
            }

            $deletedCount = 0;
            $createdCount = 0;

            foreach ($request->machines as $machineData) {
                $machineId = $machineData['machine_id'];
                $numPlaca = $machineData['numPlaca'];
                $alias = $machineData['alias'];

                // Si `numPlaca` es "0" o está vacío, eliminar todas las asociaciones de esa máquina
                if (empty($numPlaca) || $numPlaca == "0") {
                    $deletedCount += Acumulado::where('machine_id', $machineId)->count();
                    Acumulado::where('machine_id', $machineId)->delete();
                    continue;
                }

                // Verificar si la placa ya está en uso por otra máquina
                $existingAssociation = Acumulado::where('NumPlaca', $numPlaca)
                    ->where('machine_id', '!=', $machineId)
                    ->first();

                if ($existingAssociation) {
                    return response()->json([
                        'success' => false,
                        'message' => "El número de placa $numPlaca ya está asociado a otra máquina."
                    ], 400);
                }

                // Obtener los datos desde la base de datos externa
                $datosAcumulado = DB::connection($conexionComdata)
                    ->table('acumulado')
                    ->where('NumPlaca', $numPlaca)
                    ->first();

                // Si no se encuentran datos en la tabla externa, devolver error
                if (!$datosAcumulado) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se encontraron datos en la tabla acumulado para el NumPlaca: $numPlaca."
                    ], 404);
                }

                // Eliminar cualquier otra placa asociada a esta máquina antes de guardar la nueva
                Acumulado::where('machine_id', $machineId)->delete();
                $createdCount++;

                // Insertar o actualizar los datos en la base de datos local
                Acumulado::updateOrCreate(
                    ['machine_id' => $machineId],
                    [
                        'NumPlaca' => $numPlaca,
                        'local_id' => $local->id,
                        'nombre' => $alias,
                        'entradas' => $datosAcumulado->entradas ?? 0,
                        'salidas' => $datosAcumulado->salidas ?? 0,
                        'CEntradas' => $datosAcumulado->CEntradas ?? 0,
                        'CSalidas' => $datosAcumulado->CSalidas ?? 0,
                        'acumulado' => $datosAcumulado->acumulado ?? 0,
                        'CAcumulado' => $datosAcumulado->CAcumulado ?? 0,
                        'OrdenPago' => $datosAcumulado->OrdenPago ?? 0,
                        'factor' => $datosAcumulado->factor ?? 1,
                        'PagoManual' => $datosAcumulado->PagoManual ?? 0,
                        'HoraActual' => $datosAcumulado->HoraActual ?? now(),
                        'EstadoMaquina' => $datosAcumulado->EstadoMaquina ?? 'Desconocido',
                        'comentario' => $datosAcumulado->comentario ?? null,
                        'TipoProtocolo' => $datosAcumulado->TipoProtocolo ?? null,
                        'version' => $datosAcumulado->version ?? null,
                        'e1c' => $datosAcumulado->e1c ?? 0,
                        'e2c' => $datosAcumulado->e2c ?? 0,
                        'e5c' => $datosAcumulado->e5c ?? 0,
                        'e10c' => $datosAcumulado->e10c ?? 0,
                        'e20c' => $datosAcumulado->e20c ?? 0,
                        'e50c' => $datosAcumulado->e50c ?? 0,
                        'e1e' => $datosAcumulado->s1e ?? 0,
                        'e2e' => $datosAcumulado->s2e ?? 0,
                        'e5e' => $datosAcumulado->s5e ?? 0,
                        'e10e' => $datosAcumulado->s10e ?? 0,
                        'e20e' => $datosAcumulado->s20e ?? 0,
                        'e50e' => $datosAcumulado->s50e ?? 0,
                        'e100e' => $datosAcumulado->s100e ?? 0,
                        'e200e' => $datosAcumulado->s200e ?? 0,
                        'e500e' => $datosAcumulado->s500e ?? 0,
                        's1c' => $datosAcumulado->s1c ?? 0,
                        's2c' => $datosAcumulado->s2c ?? 0,
                        's5c' => $datosAcumulado->s5c ?? 0,
                        's10c' => $datosAcumulado->s10c ?? 0,
                        's20c' => $datosAcumulado->s20c ?? 0,
                        's50c' => $datosAcumulado->s50c ?? 0,
                        's1e' => $datosAcumulado->s1e ?? 0,
                        's2e' => $datosAcumulado->s2e ?? 0,
                        's5e' => $datosAcumulado->s5e ?? 0,
                        's10e' => $datosAcumulado->s10e ?? 0,
                        's20e' => $datosAcumulado->s20e ?? 0,
                        's50e' => $datosAcumulado->s50e ?? 0,
                        's100e' => $datosAcumulado->s100e ?? 0,
                        's200e' => $datosAcumulado->s200e ?? 0,
                        's500e' => $datosAcumulado->s500e ?? 0,
                        'updated_at' => now(),
                    ]
                );
            }

            // Construcción del mensaje de respuesta
            if ($deletedCount > 0 && $createdCount > 0) {
                $message = "Se eliminaron $deletedCount asociaciones y se asociaron $createdCount placas exitosamente.";
            } elseif ($deletedCount > 0) {
                $message = "Se eliminaron $deletedCount asociaciones de placas.";
            } elseif ($createdCount > 0) {
                $message = "Se asociaron $createdCount placas exitosamente.";
            } else {
                $message = "No se realizaron cambios.";
            }

            return response()->json(['success' => true, 'message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Ocurrió un error: ' . $e->getMessage()], 500);
        }
    }



    public function clearAll(Request $request)
    {
        // Verificamos si en la petición se indica que se deben eliminar asociaciones
        if ($request->has('machines')) {
            foreach ($request->machines as $machine) {
                if (isset($machine['numPlaca']) && $machine['numPlaca'] == "0") {
                    // Eliminar la máquina que tenía una placa asociada
                    Acumulado::where('machine_id', $machine['machine_id'])->delete();
                }
            }
        }

        return response()->json([
            'message' => 'Se han eliminado las asociaciones de máquinas con numPlaca = 0.'
        ], 200);
    }

    // metodo para anular el pago manual en la base de datos del comdata
    public function sendAnularPM($id_machine, $r_auxiliar, $AnularPM)
    {
        Log::info("Ejecutando sendAnularPM", [
            'id_machine' => $id_machine,
            'r_auxiliar' => $r_auxiliar,
            'AnularPM' => $AnularPM
        ]);

        // 1. Buscar el NumPlaca en la tabla acumulados
        $machine_acumulado = Acumulado::where('machine_id', $id_machine)->first();

        if (!$machine_acumulado) {
            Log::warning("⚠ No se encontró la máquina en acumulados", ['id_machine' => $id_machine]);
            return back()->with('error', 'No se encontró la máquina asociada a ningún número de placa.');
        }

        $NumPlaca = $machine_acumulado->NumPlaca;
        Log::info("✅ NumPlaca encontrado: $NumPlaca");

        // 2. Obtener la conexión con la base de datos `comdata`
        $conexion = nuevaConexionLocal('admin');

        try {
            // 3. Verificar si el registro ya existe en `nombres`
            $registro = DB::connection($conexion)->table('nombres')->where('NumPlaca', $NumPlaca)->first();

            if ($registro) {
                // Si existe, actualizar el registro
                DB::connection($conexion)->table('nombres')
                    ->where('NumPlaca', $NumPlaca)
                    ->update([
                        'nombre'     => $machine_acumulado->nombre,
                        'TypeIsAux'  => $r_auxiliar,
                        'AnularPM'   => $AnularPM
                    ]);

                Log::info("🔄 Registro actualizado en `nombres` para NumPlaca: $NumPlaca");
                return back()->with('success', 'Registro actualizado correctamente.');
            } else {
                // Si no existe, insertarlo
                DB::connection($conexion)->table('nombres')->insert([
                    'NumPlaca'   => $NumPlaca,
                    'nombre'     => $machine_acumulado->nombre,
                    'TypeIsAux'  => $r_auxiliar,
                    'AnularPM'   => $AnularPM
                ]);

                Log::info("🆕 Nuevo registro insertado en `nombres` para NumPlaca: $NumPlaca");
                return back()->with('success', 'Nuevo registro creado correctamente.');
            }
        } catch (\Exception $e) {
            Log::error("❌ Error en sendAnularPM: " . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }
}
