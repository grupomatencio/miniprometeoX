<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Local;
use Illuminate\Support\Facades\Log;

class ApiControllerGetSerialNumber extends Controller
{
    public function index (Request $request) {

        return response() -> json ([
            'success' => true,
        ],200);

        /*$serialNumber = SerialNumbers::all();
        return $serialNumber;*/

    }
    public function compareSerialNumber (Request $request) {
        $serialNumber = $request -> input('serialNumber');
        log::info('api');
        log::info($serialNumber);


        $findSerialNumber = Local::fistOrFile();

        log::info('bd');
        log::info($findSerialNumber);

        if ($findSerialNumber) {
            return response() -> json ([
                'success' => true,
            ],200);
        } else {
            return response() -> json ([
                'success' => false,
            ],404);
        }
    }
}
