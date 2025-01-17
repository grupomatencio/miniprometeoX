<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Local;
use App\Models\User;
use App\Models\Job;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Jobs\TestConexionaes;

class ApiCheckConexionesController extends Controller
{
    public function index () {

        // Probamos si hay el mismo job en cola
        $isDuplicate = buscarJob('App\\Jobs\\TestConexionaes');  // function en util.php

        // Si no existe añadimos nuevo job
        if (!$isDuplicate) {
            TestConexionaes::dispatch();
        }

        $conexiones = getEstadoConexiones();   // resultados de ultimos prubos de conexiones
        $lastTimeConexiones = getTimeConexiones(); // tiempo de ultimos prubos de conexiones
        $diferenciaTiempo = now()->diffInSeconds($lastTimeConexiones);



        Log::Info($diferenciaTiempo);

        if ($diferenciaTiempo < -45) return null;
        if (!$conexiones) return $conexiones = [false, false, false];

        return $conexiones;

    }

}
