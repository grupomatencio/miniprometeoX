<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\TestConexionaes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ApiCheckConexionesController extends Controller
{
    /* public function index()
    {
        Log::info('🔍 Verificando caché antes de obtener conexiones chekConexiones:', ['conexiones' => Cache::get('conexiones')]);

        // Verifica si el job ya está en ejecución
        $isDuplicate = buscarJob('App\\Jobs\\TestConexionaes');
        if (!$isDuplicate) {
            TestConexionaes::dispatch();
        }

        // Obtener estados de conexión y tiempo de última conexión
        $conexiones = getEstadoConexiones();
        $lastTimeConexiones = getTimeConexiones();

        Log::info('Último tiempo de conexión: ' . $lastTimeConexiones);
        Log::info('Hora actual: ' . now());

        // Validar si el tiempo de conexión es numérico y válido
        if (!is_numeric($lastTimeConexiones) || $lastTimeConexiones <= 0) {
            Log::warning('⚠️ Valor inválido para $lastTimeConexiones: ' . json_encode($lastTimeConexiones));
            return response()->json(['error' => 'Tiempo de conexión inválido'], 400);
        }

        // Calcular diferencia de tiempo
        $diferenciaTiempo = now()->diffInSeconds(Carbon::createFromTimestamp($lastTimeConexiones));
        Log::info('Diferencia en segundos: ' . $diferenciaTiempo);

        // Validar si el tiempo está fuera de rango
        if ($diferenciaTiempo < -45) {
            return response()->json(['error' => 'Tiempo de conexión fuera de rango'], 400);
        }

        // Validar que el array de conexiones tenga al menos 3 elementos
        if (!is_array($conexiones) || count($conexiones) < 3) {
            Log::warning('⚠️ Array conexiones incompleto, asignando valores por defecto.');
            $conexiones = array_pad($conexiones, 3, false);
        }

        Log::info('Estado de conexiones: ' . json_encode($conexiones));

        return response()->json(['conexiones' => $conexiones]); // ✅ SIEMPRE DEVUELVE JSON
    }*/


    public function index()
    {

        Log::info('🔍 Verificando caché antes de obtener conexiones chekConexiones antes del metodo:', ['conexiones' => Cache::get('conexiones')]);

        // Verifica si el job ya está en ejecución
        $isDuplicate = buscarJob('App\\Jobs\\TestConexionaes');
        if (!$isDuplicate) {
            TestConexionaes::dispatch();
        }

        // Obtener estados de conexión y tiempo de última conexión
        $conexiones = getEstadoConexiones();
        Log::info('Estado de conexiones ApiCheckConexiones despues del metodo:', ['conexiones' => $conexiones]);

        $lastTimeConexiones = getTimeConexiones();

        // Convertir lastTimeConexiones a Carbon
        //$lastTimeCarbon = Carbon::createFromTimestamp($lastTimeConexiones); // Asegúrate de que esto sea correcto

        // Calcular la diferencia en segundos
        //$diferenciaTiempo = now()->diffInSeconds($lastTimeConexiones);
        $diferenciaTiempo = now()->diffInSeconds(Carbon::createFromTimestamp($lastTimeConexiones));


        if ($diferenciaTiempo > 45) desconectMachines(); // si tiempo mas de 45 segundos - desconectamos machines en tabla acumulados

        if ($conexiones[1] === false) desconectMachines(); // si no hay conexiones con COMDATA - desconectamos machines en tabla acumulados

        // Devolvemos datos de tabala acumulado
        try {
            return response()->json(['conexiones' => $conexiones]); // ✅ SIEMPRE DEVUELVE JSON
        } catch (\Exception $e) {
            Log::error('Error con las conexiones');
        }
    }
}
