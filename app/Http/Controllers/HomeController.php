<?php

namespace App\Http\Controllers;
use App\Models\Acumulado;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Jobs\TestConexionaes;
use App\Jobs\ObtenerDatosTablaAcumulados;
use Exception;
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

        // Bloque para iniciar tareas en modo automatico
        /*
        $taskName = 'LaravelQueueWork';
        $command = 'queue:work';
        $this -> ensureTaskExists($taskName,$command);

        $taskName = 'LaravelScheduleWork';
        $command = 'schedule:work';
        $this -> ensureTaskExists($taskName,$command);
        */
        //$conexionConComData = nuevaConexionLocal('admin');

        // Iniciamos Jobs para comprobar conexiones y datos de TicketServer
        TestConexionaes::dispatch();
        ObtenerDatosTablaAcumulados::dispatch();

        // Obtener datos de conexiones
        $configuracionPrometeo = User::where('name', 'prometeo') -> first();
        $configuracionTS = User::where('name', 'ccm') -> first();
        $configuracionCDH = User::where('name', 'admin') -> first();

        // Enviar datos para utilizar en JavaScript
        if($configuracionPrometeo){
            session() -> flash('prometeo_ip',$configuracionPrometeo->ip);
            session() -> flash('prometeo_port',$configuracionPrometeo->port);
        }

        if($configuracionTS){
            session() -> flash('configuracionTS_IP',$configuracionTS->ip);
            session() -> flash('configuracionTS_Port',$configuracionTS->port);
        }

        if($configuracionCDH){
            session() -> flash('configuracionCDH_IP',$configuracionCDH->ip);
            session() -> flash('configuracionCDH_Port',$configuracionCDH->port);
        }
        dd(env('PASSPORT_CLIENT_ID'), env('PASSPORT_CLIENT_SECRET'), env('API_SERVER_URL'));

        return view("home");
    }

    // function para comprobar si hay task en Task Shedule
    // @return void o crea nuevo Task
    function ensureTaskExists($taskName, $command) {
        // Проверка задачи
        $output = null;
        $returnVar = null;
        exec("schtasks /query /tn \"$taskName\" 2>&1", $output, $returnVar);

        if ($returnVar !== 0) {
            // Создание задачи

            $this -> createTask($taskName, $command);

        } else {
            Log::info("Task '$taskName' already exists.");
        }
    }

    // funcción para crear Task
    function createTask($taskName, $command) {
        $phpPath = 'C:\\xampp\\php\\php.exe'; // via a PHP
        $artisanPath = 'C:\\Users\\Magarin\\miniprometeoXX\\artisan'; // via a artisan
        // $schedule = '/sc minute /mo 1'; // Cada 1 minuta

        $command = "schtasks /create /tn \"$taskName\" /tr \"$phpPath $artisanPath $command\" /sc onstart";
        // dd($command);
        try {
            exec($command, $output, $returnVar);
        } catch (Exception $e) {
            Log::error($e);
        }

        if ($returnVar === 0) {
            Log::info("Task '$taskName' created successfully.");
        } else {
            Log::error("Failed to create task '$taskName': " . implode("\n", $output));
        }
    }

}
