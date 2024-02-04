<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ControlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect('/a');
        } else if (auth()->check() && auth()->user()->role === 'support') {
            return redirect('/su');
        } else if (auth()->check() && auth()->user()->role === 'control') {
            return $next($request);
        } else if (auth()->check() && auth()->user()->role === 'sales') {
            return redirect('/sa');
        } else {

            auth()->logout();
            return redirect('/login');
        }
    }
}
