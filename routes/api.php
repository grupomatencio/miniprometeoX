<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiControllerGetSerialNumber;
use App\Http\Controllers\Api\ApiCheckConexionesController;

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

<<<<<<< HEAD
Route::get('/index', [ApiControllerGetSerialNumber::class, 'index'])->name('index');
Route::get('/checkConexion', [ApiCheckConexionesController::class, 'index'])->name('checkConexion');
Route::post('/compareSerialNumber', [ApiControllerGetSerialNumber::class, 'compareSerialNumber'])->name('compareSerialNumber');
=======
// Route::get('/index', [ApiControllerGetSerialNumber::class, 'index'])->name('index');
Route::get('/checkConexion', [ApiCheckConexionesController::class, 'index'])->name('checkConexion');
// Route::post('/compareSerialNumber', [ApiControllerGetSerialNumber::class, 'compareSerialNumber'])->name('compareSerialNumber');
>>>>>>> master
