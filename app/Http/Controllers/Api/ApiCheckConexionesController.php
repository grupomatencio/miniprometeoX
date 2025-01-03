<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Local;
<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> master
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApiCheckConexionesController extends Controller
{
    public function index () {

        $conexiones = [false,false,false]; // Conexiones por default

        // Probar conexiones con prometeo
<<<<<<< HEAD
        $url = 'http://192.168.1.41:8000/api/checkConexion';
=======
        $urlPrometeo = User::where('name', 'prometeo')->first();
        $url = 'http://'.  $urlPrometeo -> ip. ':' . $urlPrometeo -> port . '/api/checkConexion';
>>>>>>> master
        try {
            $conPrometeo = Http::get($url);
            if ($conPrometeo) {
                $conexiones[0] = true;
            }
        } catch (\Exception $e) {
            Log::info($e);
        }
<<<<<<< HEAD

        $conexionConTicketServer = nuevaConexionLocal('ccm');
        Log::info($conexionConTicketServer);
=======
/*
        $conexionConTicketServer = nuevaConexionLocal('ccm');
        Log::info(message: $conexionConTicketServer);
>>>>>>> master
        $conexiones [1] = $this -> checkConexion($conexionConTicketServer);
        $conexionConComData = nuevaConexionLocal('admin');
        Log::info($conexionConComData);
        $conexiones [2] = $this -> checkConexion($conexionConComData);
<<<<<<< HEAD

=======
*/
>>>>>>> master
        return $conexiones;

    }


    private function checkConexion ($nameConexion) {
        try {
<<<<<<< HEAD
            DB::connection($nameConexion) -> getPdo();
=======

            Log::info ($nameConexion);
            DB::connection($nameConexion) -> select ('SELECT 1');
>>>>>>> master
            // Log::info($name);

            return true;
        } catch (\Exception $e) {
            Log::info($e);
            return false;
        }

    }

}
