<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Local;
use App\Models\User;
use Carbon\Carbon;

class TestConexionaes implements ShouldQueue
{
    use Queueable;



    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        global $conexiones;
        $conexiones = [false,false,false];

        // Probar conexiones con prometeo
        $urlPrometeo = User::where('name', 'prometeo')->first();
        $url = 'http://'.  $urlPrometeo -> ip. ':' . $urlPrometeo -> port . '/api/checkConexion';
        try {
            $conPrometeo = Http::get($url);
            if ($conPrometeo) {
                $conexiones[0] = true;
            }
        } catch (\Exception $e) {

            Log::info($e);
        }


        $conexionConComData = nuevaConexionLocal('admin');
        // Log::info($conexionConComData);
        $conexiones [1] = $this -> checkConexion($conexionConComData);
        $conexionConTicketServer = nuevaConexionLocal('ccm');
        // Log::info($conexionConTicketServer);
        $conexiones [2] = $this -> checkConexion($conexionConTicketServer);

        // Guardamos información sobre resultados ultimos conexiones
        $lastTimeConnexiones = now ();
        setTimeConexiones ($lastTimeConnexiones);
        setEstadoConexiones($conexiones);
    }


    private function checkConexion ($nameConexion) {
        try {

            DB::connection($nameConexion) -> select ('SELECT 1');
            // Log::info($name);

            return true;
        } catch (\Exception $e) {
            Log::info($e);
            return false;
        }
    }

}
