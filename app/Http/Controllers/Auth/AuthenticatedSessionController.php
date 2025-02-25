<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Jobs\TestConexionaes;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\ObtenerDatosTablaAcumulados;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);


        // dd($request->password);
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            // exec('start php ' . base_path('artisan') . ' queue:work');



            // Ejecutar los commands de Artisan para swervidores y acumulados mas lento y frena la app
            //Artisan::call('check-synchronization-servidores');
            //Artisan::call('perform-acumulado-synchronization');

            // ejecutando con jobs trabajaando por detras y la app no se frena
            // Ejecutar los Jobs en segundo plano para que no frenen el login
            TestConexionaes::dispatch();
            ObtenerDatosTablaAcumulados::dispatch();

            return redirect(route('home'))->with('csrf_token', csrf_token());
        }

        // dd($request);

        return back()->withErrors([
            'name' => __('auth.failed'),
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
