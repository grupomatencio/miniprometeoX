<?php

<<<<<<< HEAD

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
=======
use Illuminate\Support\Facades\Route;
>>>>>>> master
use App\Http\Controllers\PedirAyudaController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ConfiguracionController;
<<<<<<< HEAD

=======
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckProcessorSerial;
use Illuminate\Support\Facades\Auth;
>>>>>>> master

/*
|--------------------------------------------------------------------------
| Web Routes
<<<<<<< HEAD
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

=======
|
*/

// Breeze auth
require __DIR__.'/auth.php';

// Pagina Inicio
>>>>>>> master
Route::get('/', function () {
    $error = session()->get('error');
    if (Auth::check()) {
        return redirect()->route('home')->with('error', $error);
    }
    return redirect('/login');
});

<<<<<<< HEAD
Route::get('/home',[HomeController::class, 'index'] )->name('home')->middleware(['auth', 'check.processor']);

Route::get('/pedir_ayuda', [PedirAyudaController::class, 'sendMessage'])->name('pedir.ayuda');

Route::get('/welcome', function() {
    return view('welcome');
})->name('welcome');

Route::get('/machines/search', [MachineController::class, 'search'])->name('machines.search');
Route::resource('machines', MachineController::class);

Route::get('/import', [ImportController::class, 'index'])->name('import.index');
Route::get('/import/store', [ImportController::class, 'store'])->name('import.store');

Route::post('/configuracion/company', [ConfiguracionController::class, 'guardarDatosCompania'])->name('configuracion.company');
Route::get('/configuracion/buscar', [ConfiguracionController::class, 'buscar'])->name('configuracion.buscar');
Route::resource('configuracion', ConfiguracionController::class)->names('configuracion');

Route::get('/corto', function() {return view('corto.index');});
=======
// Pagina home
Route::get('/home', [HomeController::class, 'index'])->name('home') ->middleware('auth', CheckProcessorSerial::class);

// Gestion de maquinas
Route::get('/machines/search', [MachineController::class, 'search'])->name('machines.search');
Route::resource('machines', MachineController::class)->middleware(['auth']);

// Import datos
Route::get('/import', [ImportController::class, 'index'])->name('import.index')->middleware(['auth']);
Route::get('/import/store', [ImportController::class, 'store'])->name('import.store')->middleware(['auth']);

// Configurationes
Route::post('/configuracion/save_company', [ConfiguracionController::class, 'guardarCompania'])->name('configuracion.save_company')->middleware(['auth']);
Route::post('/configuracion/company', [ConfiguracionController::class, 'guardarDatosCompania'])->name('configuracion.company')->middleware(['auth']);
Route::get('/configuracion/buscar', [ConfiguracionController::class, 'buscar'])->name('configuracion.buscar')->middleware(['auth']);
Route::resource('configuracion', ConfiguracionController::class)->names('configuracion')->middleware(['auth']);
>>>>>>> master

