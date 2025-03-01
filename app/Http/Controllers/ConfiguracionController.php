<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Local;
use App\Models\Zone;
use App\Models\Delegation;
use App\Models\Company;
<<<<<<< HEAD
=======
use Exception;
>>>>>>> master
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
<<<<<<< HEAD
=======
        $user_prometeo = User::where('name','prometeo') -> first();
>>>>>>> master
        $user_cambio = User::where('name','ccm') -> first();
        $user_comDataHost = User::where('name','admin') -> first();

        // Obtener datos de Local, zona, delegacion
        $disposicion = getDisposicion();

        $company = getCompany();

        $data = [
<<<<<<< HEAD
=======
            'user_prometeo' => $user_prometeo,
>>>>>>> master
            'user_cambio' => $user_cambio,
            'user_comDataHost' => $user_comDataHost,
            'locales' => $disposicion['locales'],
            'name_zona' =>  $disposicion['name_zona'],
            'name_delegation' => $disposicion['name_delegation'],
            'company' => $company
        ];

<<<<<<< HEAD
=======
        // Enviar IP Prometeo Principal

        session() -> flash('PROMETEO_PRINCIPAL_IP',PROMETEO_PRINCIPAL_IP);
        session() -> flash('PROMETEO_PRINCIPAL_PORT',PROMETEO_PRINCIPAL_PORT);

>>>>>>> master
        return view('configuracion.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
       // dd ($request->all());

       $request->validate([
<<<<<<< HEAD
=======
        'ip_prometeo' => ['required', 'ipv4'],
        'port_prometeo' => ['required', 'numeric', 'max:65535'],
>>>>>>> master
        'ip_cambio' => ['required', 'ipv4'],
        'port_cambio' => ['required', 'numeric', 'max:65535'],
        'ip_comdatahost' => ['required', 'ipv4'],
        'port_comdatahost' => ['required', 'numeric', 'max:65535'],
        'locales' => ['required']
    ], [
<<<<<<< HEAD
=======
        'ip_prometeo.required' => 'Este campo es obligatorio.',
        'port_prometeo.required' => 'Este campo es obligatorio.',
>>>>>>> master
        'ip_cambio.required' => 'Este campo es obligatorio.',
        'port_cambio.required' => 'Este campo es obligatorio.',
        'ip_comdatahost.required' => 'Este campo es obligatorio.',
        'port_comdatahost.required' => 'Este campo es obligatorio.',
<<<<<<< HEAD
        'ip_cambio.ipv4' => 'En este campo solo IP',
        'ip_comdatahost.ipv4' => 'En este campo solo IP',
        'port_cambio.numeric' => 'En    este campo solo digitos',
=======
        'ip_prometeo.ipv4' => 'En este campo solo IP',
        'ip_cambio.ipv4' => 'En este campo solo IP',
        'ip_comdatahost.ipv4' => 'En este campo solo IP',
        'port_prometeo.numeric' => 'En este campo solo digitos',
        'port_cambio.numeric' => 'En este campo solo digitos',
>>>>>>> master
        'port_cambio.min' => 'Numero de puerto muy grande',
        'port_comdatahost.numeric' => 'En este campo solo digitos',
        'port_comdatahost.min' => 'Numero de puerto muy grande',
        'locales.required' => 'Este campo es obligatorio.'
    ]);



       // dd ($id);

        try {

<<<<<<< HEAD

            $data = $request-> except ('_token');
=======
            $data = $request-> except ('_token');

            User::where('name','prometeo') -> update ([
                'ip' => $data['ip_prometeo'],
                'port' => $data['port_prometeo']
            ]);
>>>>>>> master
            User::where('name','ccm') -> update ([
                'ip' => $data['ip_cambio'],
                'port' => $data['port_cambio']
            ]);
            User::where('name','admin') -> update ([
                'ip' => $data['ip_comdatahost'],
                'port' => $data['port_comdatahost']
            ]);

            $serialNumberProcessor = getSerialNumber();

<<<<<<< HEAD
            try {
                $connection = DB::connection('remote_prometeo_test');

                // dd ($data['locales']);

                $result =$connection->table('licences')
                     -> where('local_id',$data['locales'])
                     -> where('serial_number',$serialNumberProcessor )
                     -> first ();

                // dd ($result);

            } catch (\Exception $exception) {
                Log::info($exception);
                $result = null;
            }

            if ($result !== null) {
=======
            $checkSerialNumber = compartirSerialNumber($serialNumberProcessor, $data['locales']);

            if ($checkSerialNumber !== null && $checkSerialNumber[0]) {
>>>>>>> master
                try {

                    DB::beginTransaction();
                    $local = Local::find($data['locales']);
                    $zone = Zone::find($local -> zone_id);
                    $delegation = Delegation::find($zone -> delegation_id);

                    $localesParaEliminar = Local::where('id', '!=', $local -> id) -> get();
                    $zonesParaEliminar = Zone::where('id', '!=',$zone -> id )-> get();
                    $delegationsParaEliminar = Delegation::where('id', '!=', $delegation -> id)-> get();

                    // dd ($localesParaEliminar);

                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    foreach ($localesParaEliminar as $loc) {
                        $loc ->delete();
                    }
                    foreach ($zonesParaEliminar as $zon) {
                        $zon ->delete();
                    }
                    foreach ($delegationsParaEliminar as $del) {
                        $del ->delete();
                    }
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');

                    DB::commit();

                } catch  (\Exception $exception) {
                    DB::rollBack();
                    Log::info($exception);
                }
            } else {
                return redirect()->back()->with("errorSerialNumber", "Error de configuración. Pongas en contacto con servicios técnicos");
            }

        } catch (\Exception $exception) {
            dd($exception);
            Log::info($exception);
        }

        return redirect()->route('configuracion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
<<<<<<< HEAD
=======
        $user_prometeo = User::where('name','prometeo') -> first();
        $user_prometeo ->ip = null;
        $user_prometeo ->port = null;
        $user_prometeo -> save();

>>>>>>> master
        $user_cambio = User::where('name','ccm') -> first();
        $user_cambio ->ip = null;
        $user_cambio ->port = null;
        $user_cambio -> save();

        $user_comDataHost = User::where('name','admin') -> first();
        $user_comDataHost ->ip = null;
        $user_comDataHost ->port = null;
        $user_comDataHost -> save();

        return redirect()->route('configuracion.index');
    }

    // Para obtener datos en modo automatico
    public function buscar() {
        $user_cambio = User::where('name','ccm') -> first();

        $filePath = 'C:\Gistra\SMI2000\Setup-TicketController\preferences.cfg';

        if (file_exists($filePath)) {


            $fileContent = file_get_contents($filePath);

            if(preg_match('/<ServerIP>(.*?)<\/ServerIP>/', $fileContent, $matches)) {
                $user_cambio ->ip = $matches[1];
            } else {
                $user_cambio ->ip = '0.0.0.0';
            }

            if(preg_match('/<ServerPort>(.*?)<\/ServerPort>/', $fileContent, $matches)) {
                $user_cambio ->port = $matches[1];
            } else {
                $user_cambio ->port = '';
            }
        }

        $user_comDataHost = new User;
        $user_comDataHost ->ip = $this -> getLocalIp ();
        $user_comDataHost ->port = 3506;

<<<<<<< HEAD
=======
        $user_prometeo = new User;
        $user_prometeo ->ip = "0.0.0.0";
        $user_prometeo ->port = 0;

>>>>>>> master
        // Obtener datos de Local, zona, delegacion
        $disposicion = getDisposicion();

        // Obtener nombre de compania
        $company = getCompany();

        $data = [
<<<<<<< HEAD
=======
            'user_prometeo' => $user_prometeo,
>>>>>>> master
            'user_cambio' => $user_cambio,
            'user_comDataHost' => $user_comDataHost,
            'locales' => $disposicion['locales'],
            'name_zona' =>  $disposicion['name_zona'],
            'name_delegation' => $disposicion['name_delegation'],
            'company' => $company
        ];

        return view('configuracion.index', compact('data'));
    }

    private function getLocalIp () {

<<<<<<< HEAD
        $output = shell_exec('ipconfig');
=======
        $output = shell_exec('ipconfig');  // Para windows
>>>>>>> master

        if (preg_match('/IPv4.*?:\s*([0-9.]+)/', $output, $matches)) {
            $localIp = $matches[1];
        }
        return $localIp;
    }


<<<<<<< HEAD
    public function guardarDatosCompania (Request $request) {

        $company = $request -> input ('$company');

        $companyNew = new Company();
        $companyNew -> id = $company['id'];
        $companyNew -> name = $company['name'];
        $companyNew -> save();

        $delegations = $request -> input ('$company.delegations');
        // Log::info($delegations);

        foreach ($delegations as $delegation) {
            $delegationNew = new Delegation();
            $delegationNew -> id = $delegation['id'];
            $delegationNew -> name = $delegation['name'];
            $delegationNew -> company_id = $delegation['company_id'];
            $delegationNew -> save ();

            $zones = $delegation['zones'];

            foreach ($zones as $zone) {

                $zoneNew = new Zone();
                $zoneNew -> id = $zone['id'];
                $zoneNew -> name = $zone['name'];
                $zoneNew -> delegation_id = $zone['delegation_id'];
                $zoneNew -> save ();

                $locals = $zone['locals'];

                foreach ($locals as $local) {

                    $localNew = new Local();
                    $localNew -> id = $local['id'];
                    $localNew -> name = $local['name'];
                    $localNew -> zone_id = $local['zone_id'];
                    $localNew -> dbconection = $local['dbconection'];
                    $localNew -> idMachines = $local['idMachines'];
                    $localNew -> save ();

                }

            }
            // Log::info($zones);
=======
    // function para guardar nombre y IP compania

    public function guardarCompania (Request $request) {

        try {

            $company = $request -> input ('$company');

            $companyNew = new Company();
            $companyNew -> id = $request['id'];
            $companyNew -> name = $request['name'];
            $companyNew -> save();

            User::where('name','prometeo') -> update ([
                'ip' => $request['ip'],
                'port' => $request['port']
            ]);

            return response()-> json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::info ($e);
            return response()-> json(['message' => 'error'], 400);
        }

    }


    // function para guardar datos de compania: delegaciones, zonas y locales
    public function guardarDatosCompania (Request $request) {

        try {

            DB::beginTransaction();

            $delegations = $request -> delegations;
            // Log::info($delegations);

            foreach ($delegations as $delegation) {
                $delegationNew = new Delegation();
                $delegationNew -> id = $delegation['id'];
                $delegationNew -> name = $delegation['name'];
                $delegationNew -> company_id = $delegation['company_id'];
                $delegationNew -> save ();

                $zones = $delegation['zones'];

                foreach ($zones as $zone) {

                    $zoneNew = new Zone();
                    $zoneNew -> id = $zone['id'];
                    $zoneNew -> name = $zone['name'];
                    $zoneNew -> delegation_id = $zone['delegation_id'];
                    $zoneNew -> save ();

                    $locals = $zone['locals'];

                    foreach ($locals as $local) {

                        $localNew = new Local();
                        $localNew -> id = $local['id'];
                        $localNew -> name = $local['name'];
                        $localNew -> zone_id = $local['zone_id'];
                        $localNew -> dbconection = $local['dbconection'];
                        $localNew -> idMachines = $local['idMachines'];
                        $localNew -> save ();

                    }

                }
                // Log::info($zones);
            }
            DB::commit();
            return response()-> json(['message' => 'success'], 200);

        } catch ( Exception $e) {
            DB::rollBack();
            Log::info ($e);
            return response()-> json(['message' => 'error'], 400);

>>>>>>> master
        }
    }
}
