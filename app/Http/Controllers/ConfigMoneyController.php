<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConfigMoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $local = Local::first();
        return view('configurationMoney.index', compact('local'));
    }

    public function syncAuxiliares()
    {

        $local = Local::first();
        Artisan::call('miniprometeo:sync-money-auxmoneystorage');
        return response()->json('Comando ejecutado y envio de datos a prometeo');
    }

    public function syncConfig()
    {

        $local = Local::first();
        Artisan::call('miniprometeo:sync-money-config');
        return response()->json('Comando ejecutado y envio de datos a prometeo');
    }

    public function syncHcInfo()
    {

        $local = Local::first();
        Artisan::call('miniprometeo:sync-money-synchronization24h');
        return response()->json('Comando ejecutado y envio de datos a prometeo');
    }
}
