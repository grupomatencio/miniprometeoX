<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


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

        // dd ('checkController0');

        $error = false; // flag para error

        // Comprobar hay configuraciones en BD o no

        if ($this -> checkConfiguracion()) {

            $local = $this -> checkConfiguracion();

            // Comprobar serial numero de processador

            $serialNumberProcessor = getSerialNumber();


            if ($serialNumberProcessor) {

                $checkSerialNumber = $this -> compartirSerialNumber($serialNumberProcessor, $local);

                // dd ($checkSerialNumber);

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

        // dd ($errorMessage);

        return $next($request);

    }



    private function compartirSerialNumber($serialNumberProcessor, $local) {

        // dd($local);

        try {
            $connection = DB::connection('remote_prometeo_test');

            // dd($local[0]->id);

            $result =$connection->table('licences')
                    -> where('local_id',$local[0]->id)
                    -> where('serial_number',$serialNumberProcessor )
                    -> first ();

                // dd($result);

                    if ($result && $result !== null) {
                        // dd($result);
                        return [true, null];
                    } else {
                        // dd($result);
                        $error = "Serial numero de processador es incorrecto";
                        session([ 'localId' => $local, "serialNumberProcessor" => $serialNumberProcessor]);

                        return [false, $error];
                    }

        }catch (\Illuminate\Database\QueryException $ex) {
             dd($ex);
            $error = "No hay conexión.";
            return [false, $error];
        } catch (\Exception $exception) {
            $error = "Hay algun error desconocido";
            return [false, $error];
        }
    }

    private function checkConfiguracion () {


        $configuracion = getDisposicion();

        if (!isset($configuracion['name_delegation']) || $configuracion['name_delegation'] == null || !isset($configuracion['name_zona']) || $configuracion['name_zona'] == null ||
            !isset($configuracion['locales']) || $configuracion['locales'] == null || is_array($configuracion['locales'])) {
            return 0;
        }

        return $configuracion['locales'];
    }
}
