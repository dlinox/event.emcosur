<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        } else if (auth()->check() && auth()->user()->role === 'support') {
            return redirect('/su');
        } else if (auth()->check() && auth()->user()->role === 'control') {
            return redirect('/co');
        } else if (auth()->check() && auth()->user()->role === 'sales') {
            return redirect('/sa');
        } else {
            auth()->logout();
            return redirect('/login');
        }
    }
}
