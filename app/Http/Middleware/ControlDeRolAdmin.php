<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ControlDeRolAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!($request->user()->role_id == 2 || $request->user()->role_id == 3)) {

            return redirect()->route('panel')->with('mensaje','Usuario no Autorizado');
        }

        return $next($request);
    }
}
