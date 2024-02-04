<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Sale;
use App\Models\Seat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function index()
    {

        //with customers
        $salesPending = Sale::where('status', 'pending')->with('customer')->get();

        $events = Event::all();

        return Inertia::render('sales/index', [
            'salesPending' => $salesPending,
            'events' => $events,
        ]);
    }

    public function events()
    {

        $events = Event::all();

        return Inertia::render('sales/events/index', ['items' => $events,]);
    }

    //Route::get('/events/{id}', [SalesController::class, 'show'])->name('show');

    public function show($id)
    {
        // le evento con sus grandstands y sus asientos
        $event = Event::with('grandstands.seats')->find($id);


        //seats where grandstand_id in 1, 2

        // $seats = Seat::whereIn('grandstand_id', [1, 2])->get();
        $seats = Seat::whereIn('grandstand_id', $event->grandstands->pluck('id'))->get();

        return Inertia::render('sales/events/show', ['item' => $event, 'seats' => $seats,]);
    }
}
