<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PedirAyudaController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ConfiguracionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Здесь вы можете зарегистрировать веб-маршруты для вашего приложения. Эти
| маршруты загружаются RouteServiceProvider и защищены middleware.
|
*/

// Breeze аутентификация
require __DIR__.'/auth.php';

// Главная страница
Route::get('/', function () {
    $error = session()->get('error');
    if (Auth::check()) {
        return redirect()->route('home')->with('error', $error);
    }
    return redirect('/login');
});

// Домашняя страница после входа
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth', 'check.processor']);

// Страница запроса помощи
// Route::get('/pedir_ayuda', [PedirAyudaController::class, 'sendMessage'])->name('pedir.ayuda');

// Приветственная страница
/*Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Управление машинами
Route::get('/machines/search', [MachineController::class, 'search'])->name('machines.search');
Route::resource('machines', MachineController::class)->middleware(['auth']);

// Импорт данных
Route::get('/import', [ImportController::class, 'index'])->name('import.index')->middleware(['auth']);
Route::get('/import/store', [ImportController::class, 'store'])->name('import.store')->middleware(['auth']);

// Настройки конфигурации
Route::post('/configuracion/company', [ConfiguracionController::class, 'guardarDatosCompania'])->name('configuracion.company')->middleware(['auth']);
Route::get('/configuracion/buscar', [ConfiguracionController::class, 'buscar'])->name('configuracion.buscar')->middleware(['auth']);
Route::resource('configuracion', ConfiguracionController::class)->names('configuracion')->middleware(['auth']);

// Пример маршрута для короткой страницы
Route::get('/corto', function () {
    return view('corto.index');
})->middleware(['auth']); */
