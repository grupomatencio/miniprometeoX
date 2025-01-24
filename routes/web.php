<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PedirAyudaController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\SyncMoneyController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckProcessorSerial;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|
*/

// Breeze auth
require __DIR__.'/auth.php';

// Pagina Inicio
Route::get('/', function () {
    $error = session()->get('error');
    if (Auth::check()) {
        return redirect()->route('home')->with('error', $error);
    }
    return redirect('/login');
});

// Pagina home
Route::get('/home', [HomeController::class, 'index'])->name('home') ->middleware('auth', CheckProcessorSerial::class);

// Gestion de maquinas
Route::get('/machines/search', [MachineController::class, 'search'])->name('machines.search');
Route::resource('machines', MachineController::class)->middleware(['auth']);

// Import datos
Route::get('/import', [ImportController::class, 'index'])->name('import.index')->middleware(['auth']);
Route::get('/import/store', [ImportController::class, 'store'])->name('import.store')->middleware(['auth']);

Route::get('/syncmoney', SyncMoneyController::class);

// Configurationes
Route::post('/configuracion/save_company', [ConfiguracionController::class, 'guardarCompania'])->name('configuracion.save_company')->middleware(['auth']);
Route::post('/configuracion/company', [ConfiguracionController::class, 'guardarDatosCompania'])->name('configuracion.company')->middleware(['auth']);
Route::get('/configuracion/buscar', [ConfiguracionController::class, 'buscar'])->name('configuracion.buscar')->middleware(['auth']);
Route::resource('configuracion', ConfiguracionController::class)->names('configuracion')->middleware(['auth']);

// traer datos de CLIENT ruta de pruebas
Route::post('/getDataClient', [ConfiguracionController::class, 'getDataClient']);

// para guardar los datos que recibimos de la peticion
Route::post('/saveClientData', [ConfiguracionController::class, 'saveClientData']);

