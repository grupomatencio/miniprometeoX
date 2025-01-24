<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SyncMoneyController extends Controller
{
    public function __invoke(Request $request)
    {
        // Aquí va tu lógica para sincronizar dinero
        return response()->json(['message' => 'Sincronización de dinero realizada']);
    }
}
