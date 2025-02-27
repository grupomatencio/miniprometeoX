<?php

namespace App\Http\Controllers;

use DOMElement;
use SimpleXMLElement;
use App\Models\Machine;
use App\Models\Delegation;
use Illuminate\Http\Request;
use App\Models\AuxMoneyStorage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

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

            $auxmoneys = AuxMoneyStorage::orderByRaw('CAST(TypeIsAux AS UNSIGNED) ASC')->get();
            //dd($auxmoneys);
            $machines = Machine::where('type', 'single')
                ->orWhere('type', null)
                ->get();

            return view("machines.index", compact("machines", "auxmoneys"));
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
        $delegation = Delegation::with('zones.locals')->first();
        // para obtener el primer y unico local de miniprometeo una vez se configura todo
        $firstLocal = $delegation->zones->flatMap->locals->first();
        return view("machines.create", compact('delegation', 'firstLocal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());

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

        /// mirar lo ID para arreglarlo
        $machine->local_id = $request->local_id;
        //$machine->bar_id = null;
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
            'r_auxiliar.*' => ['numeric']
        ], [
            'alias.*.required' => 'El alias de la máquina es obligatorio.',
            'r_auxiliar.*.numeric' => 'En este campo solo deben ir dígitos.',
        ]);


        Machine::find($id)->update([
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
            ->orWhereRaw('LOWER(identificador) LIKE ?', $searchTerm)->get();

        // Retorna la vista con los resultados de la búsqueda
        return view("machines.index", compact("machines"));
    }


    public function syncTypesTickets()
    {

        $exitCode = Artisan::call('miniprometeo:perform-sync-types-tickets');

        if ($exitCode === 0) {
            session()->flash('success', 'Sincronización completada exitosamente.');
        } else {
            session()->flash('error', 'Error en la sincronización.');
        }

        return redirect()->back(); // Redirige a la misma página para mostrar los mensajes en la vista

    }

    public function sendAuxiliares(Request $request)
    {

        //dd($request->all());
        Log::info('🔹 Iniciando sendAuxiliares...');

        // Validar entrada
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'ip_address' => 'required|ip'
        ]);

        // 🔹 Obtener datos
        $username = escapeshellarg($request->input('username'));
        $password = escapeshellarg($request->input('password'));
        $ip = $request->input('ip_address');

        Log::info("🔹 IP recibida: {$ip}");

        // 🔹 Definir rutas
        $driveLetter = "Z:"; // Puedes cambiarla si está en uso
        $networkPath = "\\\\{$ip}\\Gistra";
        $sharedPath = "{$driveLetter}\\SMI2000\\Setup-TicketController\\TicketControllerPreferences.cfg";

        // 🔹 Desmontar unidad si ya está conectada
        exec("net use {$driveLetter} /delete /y");
        exec("dir {$driveLetter}", $output);
        Log::info("🔹 Contenido de {$driveLetter}: " . implode("\n", $output));

        // 🔹 Conectar unidad de red con credenciales
        $command = "net use {$driveLetter} \"{$networkPath}\" /user:{$username} {$password}";
        Log::info("🔹 Ejecutando comando: {$command}");

        exec($command, $output, $result);
        Log::info("🔹 Salida del comando: " . implode("\n", $output));

        if ($result !== 0) {
            Log::error("❌ Error al conectar la carpeta compartida. Código: {$result}");
            return back()->with('error', 'No se pudo conectar a la carpeta compartida. Verifica las credenciales.');
        }

        Log::info("✅ Conectado a la carpeta compartida en {$driveLetter}");

        // 🔹 Verificar que la unidad está montada correctamente
        if (!File::exists($driveLetter)) {
            Log::error("❌ La unidad {$driveLetter} no está accesible.");
            exec("net use {$driveLetter} /delete /y");
            return back()->with('error', 'No se puede acceder a la unidad de red.');
        }

        // 🔹 Verificar que el archivo XML existe
        if (!File::exists($sharedPath)) {
            Log::error("❌ El archivo XML no se encuentra en la ruta: {$sharedPath}");
            exec("net use {$driveLetter} /delete /y");
            return back()->with('error', 'No se encontró el archivo XML.');
        }

        // Verificar acceso al archivo
        try {
            // Intenta abrir el archivo para asegurarte de que tienes acceso
            $fileHandle = fopen($sharedPath, 'r');
            if (!$fileHandle) {
                throw new \Exception("No se puede acceder al archivo XML.");
            }
            fclose($fileHandle);
        } catch (\Exception $e) {
            Log::error("❌ Error al acceder al archivo: " . $e->getMessage());
            exec("net use {$driveLetter} /delete /y");
            return back()->with('error', 'No se puede acceder al archivo XML: ' . $e->getMessage());
        }

        try {
            // 🔹 Obtener todas las máquinas con r_auxiliar
            $machines = Machine::whereNotNull('r_auxiliar')->get();
            Log::info("🔹 Máquinas encontradas: " . count($machines));

            // 🔹 Cargar el XML en DOMDocument
            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->load($sharedPath);

            Log::info("🔹 XML cargado correctamente");

            // 🔹 Obtener el elemento raíz
            $xpath = new \DOMXPath($dom);

            // Buscar <AssignToAux>
            $assignToAux = $xpath->query('//AssignToAux')->item(0);

            // Si no existe, crearlo después de </Aux10Concepts>
            if (!$assignToAux) {
                Log::warning("⚠️ AssignToAux no encontrado, creando el nodo en la posición correcta...");

                // Encontrar <Aux10Concepts> y su nodo siguiente
                $aux10Concepts = $xpath->query('//Aux10Concepts')->item(0);
                $assignToAuxEnableState = $xpath->query('//AssignToAuxEnableState')->item(0);

                if ($aux10Concepts) {
                    // Crear el nuevo nodo <AssignToAux>
                    $assignToAux = $dom->createElement('AssignToAux');

                    // Insertar justo después de <Aux10Concepts>
                    if ($aux10Concepts->parentNode) {
                        if ($assignToAuxEnableState) {
                            $aux10Concepts->parentNode->insertBefore($assignToAux, $assignToAuxEnableState);
                        } else {
                            $aux10Concepts->parentNode->appendChild($assignToAux);
                        }
                    }
                    Log::info("✅ Nodo AssignToAux creado correctamente.");
                } else {
                    throw new \Exception("No se encontró el nodo <Aux10Concepts> en el XML.");
                }
            }

            // 🔹 Procesar las máquinas y actualizar/agregar los nodos <CAssignToAux>
            $entries = $xpath->query('//AssignToAux/CAssignToAux');

            foreach ($machines as $machine) {
                $found = false;
                Log::info("🔹 Procesando máquina: {$machine->alias} - r_auxiliar: {$machine->r_auxiliar}");

                foreach ($entries as $assign) {
                    // Verificar que $assign es un DOMElement
                    if (!$assign instanceof \DOMElement) {
                        continue;
                    }

                    $keyNode = $assign->getElementsByTagName('Key')->item(0);
                    if ($keyNode && $keyNode->nodeValue === $machine->alias) {
                        // Si encontramos la máquina, actualizamos su valor
                        Log::info("✅ Actualizando alias {$machine->alias} con r_auxiliar {$machine->r_auxiliar}");

                        // Intentamos obtener el nodo <Value>
                        $valueNode = $assign->getElementsByTagName('Value')->item(0);

                        if ($valueNode instanceof DOMElement) {
                            // Si existe, actualizamos el valor
                            $valueNode->nodeValue = $machine->r_auxiliar;
                        } else {
                            // Si no existe <Value>, lo creamos y lo agregamos
                            $newValueNode = $dom->createElement('Value', $machine->r_auxiliar);
                            $assign->appendChild($newValueNode);
                        }

                        $found = true;
                    }
                }

                // Si no existe, agregar nuevo nodo <CAssignToAux>
                if (!$found) {
                    Log::info("➕ Añadiendo nueva entrada para {$machine->alias}");

                    $newEntry = $dom->createElement('CAssignToAux');
                    $newEntry->appendChild($dom->createElement('Key', $machine->alias));
                    $newEntry->appendChild($dom->createElement('Value', $machine->r_auxiliar));
                    $newEntry->appendChild($dom->createElement('DefaultAuxConcept', ''));

                    $assignToAux->appendChild($newEntry);
                }
            }

            // 🔹 Guardar en un archivo temporal antes de reemplazar el original
            $tempPath = storage_path('temp.xml');
            $dom->save($tempPath);

            // 🔹 Verificar que el archivo temporal se guardó correctamente
            if (!File::exists($tempPath)) {
                throw new \Exception("El archivo temporal no se creó correctamente.");
            }

            Log::info("✅ Archivo temporal creado en: {$tempPath}");

            // 🔹 Copiar el archivo temporal al destino final
            File::copy($tempPath, $sharedPath);

            // 🔹 Verificar que la copia fue exitosa
            if (!File::exists($sharedPath)) {
                throw new \Exception("El archivo XML no se copió correctamente al destino.");
            }

            Log::info("✅ Archivo XML actualizado correctamente en {$sharedPath}");

            // 🔹 Eliminar el archivo temporal
            File::delete($tempPath);

            return back()->with('success', 'Archivo actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("❌ Error en sendAuxiliares: " . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al procesar el archivo: ' . $e->getMessage());
        }
    }
}
