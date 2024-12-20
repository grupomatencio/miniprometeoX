<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Services\getProcessorSerialNumber;
use App\Models\SerialNumbers;

class PedirAyudaController extends Controller
{


    public function sendMessage()
    {

    $serialNumber =  session('serialNumberProcessor');
    $localId =  session('localId');


         if ($serialNumber && $localId) {
                // URL API Prometeo


                $url = 'http://192.168.1.41:8000/api/verify-serial-change';

                // Enviamos un Post con datos
                $response = Http::post($url, [
                    'serialNumber' => $serialNumber,
                    'local_id' => $localId,
                ]);
            /*
                $url = 'http://192.168.1.41:8000/api/test';

                // Enviamos un Post con datos
                $response = Http::get($url); */
                $data = $response -> json ();



                dd($data);

                // Respusta
                if ($response->successful()) {
                    $message = 'Datos enviados, espere una respusta.';
                    return redirect()->back()->with('success', $message);
                } else {
                    $message = 'El servidor no está disponible, inténtalo de nuevo más tarde.';
                }
        } else {
            $message = 'Datos son incompletos. Póngase en contacto con el soporte técnico por teléfono.';
        }
        // return redirect()->back()->with('warning', $message);
         $message = 'Datos enviados, espere una respusta.';
         return redirect()->back()->with('success', $message);
    }
}
