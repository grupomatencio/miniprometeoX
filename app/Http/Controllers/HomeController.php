<?php

namespace App\Http\Controllers;
use App\Models\Acumulado;
use App\Models\User;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Log;
>>>>>>> master


use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
<<<<<<< HEAD
=======

    /*
>>>>>>> master
    public function __construct()
    {
        $this->middleware('auth');
    }
<<<<<<< HEAD
=======
        */
>>>>>>> master

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()

    {
<<<<<<< HEAD
        $acumulados = Acumulado::all();

        $configuracionTS = User::where('name', 'ccm') -> first();
        $configuracionCDH = User::where('name', 'admin') -> first();

=======
        // dd ('home');

        Log::info('Home:', request()->cookies->all());

        $acumulados = Acumulado::all();

        $configuracionPrometeo = User::where('name', 'prometeo') -> first();
        $configuracionTS = User::where('name', 'ccm') -> first();
        $configuracionCDH = User::where('name', 'admin') -> first();


        if($configuracionPrometeo){
            session() -> flash('prometeo_ip',$configuracionPrometeo->ip);
            session() -> flash('prometeo_port',$configuracionPrometeo->port);
        }

>>>>>>> master
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
