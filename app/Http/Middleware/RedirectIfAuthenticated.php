<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                //si el role es admin
                if (Auth::user()->role === 'admin') {
                    return redirect('/a');
                }
                //si el role es support
                else if (Auth::user()->role === 'support') {
                    return redirect('/su');
                }

                else if (Auth::user()->role === 'control') {
                    return redirect('/co');
                }

                else if (Auth::user()->role === 'sales') {
                    return redirect('/sa');
                }
            }
        }

        return $next($request);
    }
}
