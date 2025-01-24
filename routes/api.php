<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiControllerGetSerialNumber;
use App\Http\Controllers\Api\ApiCheckConexionesController;
use App\Http\Controllers\Api\ApiCheckAcumuladoController;
use App\Http\Controllers\DataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Route::get('/index', [ApiControllerGetSerialNumber::class, 'index'])->name('index');
Route::get('/checkConexion', [ApiCheckConexionesController::class, 'index'])->name('checkConexion');
Route::get('/checkAcumulados', [ApiCheckAcumuladoController::class, 'index'])->name('checkAcumulados');
// Route::post('/compareSerialNumber', [ApiControllerGetSerialNumber::class, 'compareSerialNumber'])->name('compareSerialNumber');

// rutas para trabajar con los datos de miniprometeo y prometeo

Route::get('/get-data/{table}', [DataController::class, 'getData']); // Obtener datos
Route::post('/save-data/{table}', [DataController::class, 'saveData']); // Guardar datos
Route::post('/send-data/{table}', [DataController::class, 'sendData']); // Enviar datos
