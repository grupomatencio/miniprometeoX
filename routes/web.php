<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PedirAyudaController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ConfiguracionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    $error = session()->get('error');
    if (Auth::check()) {
        return redirect()->route('home')->with('error', $error);
    }
    return redirect('/login');
});

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

