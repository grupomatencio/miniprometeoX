<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Jobs\ObtenerDatosTablaAcumulados;

use App\Models\Acumulado;
use App\Models\Job;

class ApiCheckAcumuladoController extends Controller
{
    public function index () {

        // Probamos si hay el mismo job en cola
        $isDuplicate = buscarJob('App\\Jobs\\ObtenerDatosTablaAcumulados');  // function en util.php

        // Si no existe añadimos nuevo job
        if (!$isDuplicate) {
            ObtenerDatosTablaAcumulados::dispatch();
        }

        // Devolvemos datos de tabala acumulado
        try {
            $acumulados = Acumulado::all();

        } catch (\Exception $e) {
            Log::error ('Error de leido tabala Acumulados');
        }

        return $acumulados;
    }
}
