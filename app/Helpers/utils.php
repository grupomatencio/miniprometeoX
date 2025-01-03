<?php

use App\Models\Local;
use App\Models\Zone;
use App\Models\User;
use App\Models\Delegation;
use App\Models\Company;
use App\Models\lastUserMcDate;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;


// IP y Puerto prometeo Principal

define('PROMETEO_PRINCIPAL_IP', "192.168.1.41");
define('PROMETEO_PRINCIPAL_PORT', "8000");
function nuevaConexion($local)
{
    $localDate = Local::find($local);
    $connectionName = 'mariadb';

    // Decodificar el JSON de la conexión y obtener la primera conexión (índice 0)
    $datosConexion = json_decode($localDate->dbconection);

    $conexionCero = $datosConexion[0];  // Asegúrate de que el JSON sea un array y accede al primer elemento

    // Modificar la configuración de la conexión de base de datos
    config([
        'database.connections.' . $connectionName . '.host' => $conexionCero->ip,
        'database.connections.' . $connectionName . '.port' => $conexionCero->port,
        'database.connections.' . $connectionName . '.database' => $conexionCero->database,
        'database.connections.' . $connectionName . '.username' => $conexionCero->username,
        'database.connections.' . $connectionName . '.password' => $conexionCero->password,

    ]);

    // Limpiar la conexión para que se apliquen los nuevos valores
    DB::purge($connectionName);

    return $connectionName;
}

//


// conexion depende de nombre usuario
function nuevaConexionLocal($name)
{
    $user = User::where('name', $name) ->first();
    $connectionName = 'mariadb'. '-'. $name;

    if ($name === 'admin') {
        $database = 'ticketserver';
    } else {
        $database = "comdata";
    }

    Log::info($user);
    // Log::info('util');
    $passDecrypt = Crypt::decryptString($user->password);

    Log::info($passDecrypt);

    DB::purge($connectionName);

    try {
        // Modificar la configuración de la conexión de base de datos
        config([
            'database.connections.' . $connectionName . '.host' => $user->ip,
            'database.connections.' . $connectionName . '.port' => $user->port,
            'database.connections.' . $connectionName . '.database' => $database,
            'database.connections.' . $connectionName . '.username' => $user->name,
            'database.connections.' . $connectionName . '.password' => $passDecrypt,
            'database.connections.' . $connectionName . '.driver' => 'mysql',
            'database.connections.' . $connectionName . '.options' => [PDO::ATTR_PERSISTENT=> false, PDO::ATTR_TIMEOUT => 2],
        ]);

        $config = config ('database.connections'.$database);

        // Log::info('database.connections.' . $connectionName . '.host');

        // Limpiar la conexión para que se apliquen los nuevos valores
    }catch (\Exception $e) {
        Log::info($e);

    }

    return $connectionName;
}


// function para obtener serial numer de Procesador
function getSerialNumber() :string
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $serial = shell_exec('wmic cpu get ProcessorId');

        // Limpiar el serial eliminando saltos de línea y espacios extra
        $serial = preg_replace('/\s+/', ' ', trim($serial));

        // Explodemos el serial por el espacio para obtener la parte deseada
        $parts = explode(' ', $serial);

        // Extraemos el primer valor que debería ser el ProcessorId
        $processorId = $parts[1]; // Asumiendo que el ProcessorId es el segundo elemento

        return $processorId;
    }

    // Para Linux
    elseif (strtoupper(substr(PHP_OS, 0, 6)) === 'LINUX') {
        $output = "ID: C1 06 08 00 FF FB EB BF";  // solo para probar
        //shell_exec('sudo /usr/sbin/dmidecode -t 4 | grep ID');

        if ($output) {
            preg_match('/ID:\s*([a-fA-F0-9\s]+)/', $output, $matches);
            $serial = isset($matches[1]) ? trim($matches[1]) : null;
        } else {
            $serial = null;
        }
        return trim($serial);
    }

    return null; // Si hay otro
}

    // Para obtener datos de local, zona, delegation
    function getDisposicion () {
        $locales = Local::all();
        $zones=Zone::all();
        $delegation = Delegation::all();
        $name_zona = "";
        $name_delegation = "";

        if (count($zones) == 1) {
            $name_zona = $zones[0] -> name;
        }

        if (count($delegation) == 1) {
            $name_delegation = $delegation[0] ->name;
        }

        return $disposicion = [
            'locales' => $locales,
            'name_zona' =>  $name_zona,
            'name_delegation' => $name_delegation
        ];
    }

    // function para comprobar si hay Compania en BD
    function getCompany () {
        try {
            $company = Company::first();

            if ($company) return $company -> name;
            else return null;
        } catch (\Exception $e) {
            Log::info($e);
            return null;
        }
    }


    // function para conexion con prometeo y comprobar licencia
    function compartirSerialNumber($serialNumberProcessor, $local) {

        try {

                    // Probar conexiones con prometeo
                    $urlPrometeo = User::where('name', 'prometeo')->first();
                    $company = Company::first();
                    $url = 'http://'. PROMETEO_PRINCIPAL_IP . ':8000/api/verify-licence-company';

                    // dd ($local);

                    try {
                        $response = Http::post($url, [
                            'local_id' => $local,
                            'serialNumber' => $serialNumberProcessor,
                            'company' => $company -> name
                        ]);
                        // dd ($response-> json());

                    } catch (\Exception $e) {

                        Log::info($e);
                    }

                    $result = $response -> json();

                    if ($result !== null && $result['success']) {

                        return [true, null];
                    } else {

                        $error = "Serial numero de processador es incorrecto";
                        session([ 'localId' => $local, "serialNumberProcessor" => $serialNumberProcessor]);

                        return [false, $error];
                    }

        }catch (\Illuminate\Database\QueryException $ex) {
            Log::info($ex);
            $error = "No hay conexión.";
            return [false, $error];
        } catch (\Exception $exception) {
            $error = "Hay algun error desconocido";
            return [false, $error];
        }
    }
