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

            if (auth()->user()->role === 'admin') {
                return redirect('/a');
            } else if (auth()->user()->role === 'support') {
                return redirect('/su');
            } else if (auth()->user()->role === 'control') {
                return redirect('/co');
            } else if (auth()->user()->role === 'sales') {
                return redirect('/sa');
            } else {

                auth()->logout();

                return back()->withErrors([
                    'error' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
                ]);
            }
        }
        return back()->withErrors([
            'error' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
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
