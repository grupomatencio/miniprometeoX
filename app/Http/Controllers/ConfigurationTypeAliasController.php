<?php

namespace App\Http\Controllers;

use App\Models\Local;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigurationTypeAliasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $local = Local::first();

        // Conexión remota a la base de datos
        $conexion = nuevaConexionLocal('ccm');

        // Obtener todos los tipos de tickets
        $tickets = DB::connection($conexion)
            ->table('tickets')
            ->orderBy('Type', 'DESC')
            ->get();

        // Obtener todos los alias de tipo asociado a las máquinas
        $typeAlias = DB::table('type_alias')->get()->keyBy('type'); // Agrupa los alias por tipo

        // Obtener todas las máquinas
        $machines = Machine::all(); // Recupera todos los registros del modelo Machine

        // Crear una colección para almacenar los tickets únicos
        $uniqueTickets = collect();

        foreach ($tickets as $ticket) {
            // Verifica si ya existe en la colección
            if (!$uniqueTickets->contains('Type', $ticket->Type)) {
                $uniqueTickets->push($ticket); // Agrega el ticket completo
            }
        }

        // Pasar los datos a la vista
        return view('configurationTypeAlias.index', compact('local', 'uniqueTickets', 'machines', 'typeAlias'));
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
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
