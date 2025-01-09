<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Jobs\ObtenerDatosTablaAcumulados;

use App\Models\Acumulado;

class ApiCheckAcumuladoController extends Controller
{
    public function index () {

        ObtenerDatosTablaAcumulados::dispatch();

        try {
            $acumulados = Acumulado::all();

        } catch (\Exception $e) {
            Log::error ('Error de leido tabala Acumulados');
        }

        return $acumulados;
    }
}
