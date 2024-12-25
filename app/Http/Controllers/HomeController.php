<?php

namespace App\Http\Controllers;
use App\Models\Acumulado;
use App\Models\User;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
        */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()

    {
        $acumulados = Acumulado::all();

        $configuracionTS = User::where('name', 'ccm') -> first();
        $configuracionCDH = User::where('name', 'admin') -> first();

        if($configuracionTS){
            session() -> flash('configuracionTS_IP',$configuracionTS->ip);
            session() -> flash('configuracionTS_Port',$configuracionTS->port);
        }

        if($configuracionCDH){
            session() -> flash('configuracionCDH_IP',$configuracionCDH->ip);
            session() -> flash('configuracionCDH_Port',$configuracionCDH->port);
        }

        return view("home", compact("acumulados"));
    }
}
