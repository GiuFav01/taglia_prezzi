<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class EnsureTokenIsValid
{

    public function handle(Request $request, Closure $next)
    {
        $token = Cookie::get('auth_token');

        if (!$token || !Auth::check()) {
            Log::info('Accesso non autorizzato: Token o autenticazione mancante.', [
                'token' => $token,
                'auth_check' => Auth::check(),
            ]);

            if ($request->routeIs('auth.index', 'login')) {
                return $next($request);
            }
            return redirect()->route('auth.index')->with('error', 'Devi effettuare il login.');
        }

        return $next($request);
    }
}
