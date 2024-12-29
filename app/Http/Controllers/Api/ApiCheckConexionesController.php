<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Local;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApiCheckConexionesController extends Controller
{
    public function index () {

        $conexiones = [false,false,false]; // Conexiones por default

        // Probar conexiones con prometeo
        $url = 'http://192.168.1.41:8000/api/checkConexion';
        try {
            $conPrometeo = Http::get($url);
            if ($conPrometeo) {
                $conexiones[0] = true;
            }
        } catch (\Exception $e) {
            Log::info($e);
        }

        $conexionConTicketServer = nuevaConexionLocal('ccm');
        Log::info($conexionConTicketServer);
        $conexiones [1] = $this -> checkConexion($conexionConTicketServer);
        $conexionConComData = nuevaConexionLocal('admin');
        Log::info($conexionConComData);
        $conexiones [2] = $this -> checkConexion($conexionConComData);

        return $conexiones;

    }


    private function checkConexion ($nameConexion) {
        try {
            DB::connection($nameConexion) -> getPdo();
            // Log::info($name);

            return true;
        } catch (\Exception $e) {
            Log::info($e);
            return false;
        }

    }

}
