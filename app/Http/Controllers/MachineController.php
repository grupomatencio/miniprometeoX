<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $machines = Machine::where('type', 'single')
                                -> orWhere('type',null)
                                -> get();

            return view("machines.index", compact("machines"));
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("machines.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'alias' => ['required'],
            'model' => ['required'],
            'codigo' => ['required', 'regex:/^[A-Za-z0-9]{3}$/'],
            'serie' => ['required', 'regex:/^\d{2}( [A-Za-z]|[A-Za-z]{2})$/'],
            'numero' => ['required', 'digits:6'],
            //'local' => ['required'],
        ], [
            'name.required' => 'El nombre de la máquina es obligatorio.',
            'alias.required' => 'El alias de la máquina es obligatorio.',
            'model.required' => 'El modelo de la máquina es obligatorio.',
            'codigo.required' => 'El código de la máquina es obligatorio.',
            'codigo.regex' => 'El código debe ser una cadena de exactamente 3 caracteres alfanuméricos.',
            'serie.required' => 'La serie de la máquina es obligatoria.',
            'serie.regex' => 'La serie debe ser una cadena de 4 caracteres, con los primeros 2 siendo números y los últimos 2 siendo dos letras o un espacio seguido de una letra.',
            'numero.required' => 'El número de la máquina es obligatorio.',
            'numero.digits' => 'El número debe tener exactamente 6 dígitos.',
            //'local.required' => 'El local de la máquina es obligatorio.',
        ]);

        $local = explode(":", $request->local);
        $identificador = $request->model . ':' . $request->codigo . ':' . $request->serie . ':' . $request->numero;

        $machine = new Machine();
        $machine->identificador = $identificador;
        $machine->name = $request->name;
        $machine->alias = $request->alias;

        $machine->local_id = 1;
        $machine->bar_id = 1;
        $machine->delegation_id = $request->delegation_id;

        $machine->timestamps = false;

        $machine->save();
        return redirect()->route('machines.index', $request->delegation_id);
        //dd($identificador);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $machine = Machine::findOrFail($id);
        $identificador = explode(':', $machine->identificador);

        $mode = $identificador[0];
        $codigo = $identificador[1];
        $serie = $identificador[2];
        $numero = $identificador[3];


        return view('machines.edit', compact('machine',  'mode', 'codigo', 'serie', 'numero'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());

        $request->validate([
            'alias.*' => ['required'],
            'r_auxiliar.*' => ['required', 'numeric', 'unique:machines,r_auxiliar,' . $id]
        ], [
            'alias.*.required' => 'El alias de la máquina es obligatorio.',
            'r_auxiliar.*.required' => 'El número de la máquina es obligatorio.',
            'r_auxiliar.*.numeric' => 'En este campo solo deben ir dígitos.',
            'r_auxiliar.*.unique' => "Este número ya está en uso."
        ]);


        Machine::find($id) -> update ([
            'alias' => $request->alias[$id],
            'r_auxiliar' => $request->r_auxiliar[$id]
        ]);

        return redirect()->route('machines.index', $request->delegation_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Machine $machine)
    {
        $machine->delete();
        return redirect()->route('machines.index', $machine->delegation_id);
    }

    public function search(Request $request)
    {

        // Obtiene el término de búsqueda del input
        $searchTerm = $request->input('search');
        $searchTerm = '%' . $searchTerm . '%'; // Ajuste para búsqueda parcial


        // Busca máquinas que coinciden con el término de búsqueda
        $machines = Machine::whereRaw('LOWER(name) LIKE ?',  $searchTerm)
                    ->orWhereRaw('LOWER(identificador) LIKE ?', $searchTerm) ->get();

        // Retorna la vista con los resultados de la búsqueda
        return view("machines.index", compact("machines"));
    }

}
