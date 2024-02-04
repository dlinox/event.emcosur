<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('auth/login');
    }

    public function signIn(Request $request)
    {

        if (Auth::attempt($request->only('email', 'password'))) {

            if (Auth::user()->role === 'admin') {
                return redirect('/a/events');
            } else if (Auth::user()->role === 'support') {
                return redirect('/su');
            } else if (Auth::user()->role === 'control') {
                return redirect('/co');
            } else if (Auth::user()->role === 'sales') {
                return redirect('/sa');
            } else {

                auth()->logout();

                return back()->with([
                    "errors" => 'Ocurrio un error al intentar iniciar sesión.',
                ]);
             
            }
        }
        return back()->with([
            "errors" => 'Ocurrio un error al intentar iniciar sesión.',
        ]);
    }
    public function signUp()
    {
    }
    public function signOut()
    {
        Auth::logout();
        return redirect('/');
    }
}
