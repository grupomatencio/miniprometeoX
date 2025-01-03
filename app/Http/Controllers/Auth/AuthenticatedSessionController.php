<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

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

         Log::info('ASController1', request()->cookies->all());

         // dd($request->password);
         if (Auth::attempt(['name' => $request->name, 'password' => $request->password], $request->boolean('remember'))) {
                $request->session()->regenerate();
             // dd ('stop1');
             Log::info('tyt');

             // $middleware = app(\App\Http\Middleware\CheckProcessorSerial::class);
            // dd($middleware);

             // dd($request->password);
             Log::info('ASController2', request()->cookies->all());
             // dd ('stop');
             return redirect(route('home')) ->with('csrf_token', csrf_token());
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
