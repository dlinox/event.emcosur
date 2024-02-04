<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ControlController extends Controller
{
    public function index()
    {
        return Inertia::render('control/index');
    }
}
