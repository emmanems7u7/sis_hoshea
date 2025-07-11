<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
class ArtisanPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::get('artisan_access_granted') !== true) {
            return redirect()->route('artisan.verificacion')->with('error', 'Debes ingresar la contraseña para acceder.');
        }
        return $next($request);
    }

}
