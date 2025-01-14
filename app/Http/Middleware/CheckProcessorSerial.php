<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use app\Models\User;


use App\Services\getProcessorSerialNumber;

class CheckProcessorSerial
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // Comprobar hay configuraciones en BD o no

        if ($this -> checkConfiguracion()) {

            $local = $this -> checkConfiguracion();

            // Obtener serial numero de processador

            $serialNumberProcessor = getSerialNumber();

            // Comprobar serial numero de processador
            if ($serialNumberProcessor) {

                $checkSerialNumber = compartirSerialNumber($serialNumberProcessor, $local);

                if($checkSerialNumber[0] == false){

                    $errorMessage = $checkSerialNumber[1];
                    session() -> flash('error',$errorMessage);
                    $error = true;
                } else {
                    session() ->forget('error');
                }

            } else {
                $errorMessage = 'No se puede determinar el número de procesador para la autorización.';
                session() -> flash('error',$errorMessage);
                $error = true;
            }
        } else {

            $errorMessage = 'El servidor no está configurado. Configure el servidor.';
            session() -> flash('error',$errorMessage);
            $error = true;

        }
        return $next($request);

    }

    // function para probar hay configuraciones o no
    // @return $local - local o null
    private function checkConfiguracion () {


        // Obtener datos de local
        $configuracion = getDisposicion();

        // Comprobar hay datos o no
        if (!isset($configuracion['name_delegation']) || $configuracion['name_delegation'] == null || !isset($configuracion['name_zona']) || $configuracion['name_zona'] == null ||
            !isset($configuracion['locales']) || $configuracion['locales'] == null || is_array($configuracion['locales'])) {
            return 0;
        }
        if ($configuracion['locales'] -> isNotEmpty()) {
            $local = $configuracion['locales'] -> first() -> id;
        } else {
            $local = null;
        }
        return $local;
    }
}
