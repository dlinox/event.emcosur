<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Grandstand;
use App\Models\Sale;
use App\Models\Seat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function index()
    {

        //with customers
        //con join agragar el nomnre del evento
        $salesPending = Sale::select('sales.*', 'events.name as eventName')->where('status', 'pending')->join('events', 'sales.event_id', '=', 'events.id')->with('customer')->get();

        // $salesPending = Sale::where('status', 'pending')->with('customer')->get();

        $events = Event::where('is_active', true)->get();

        // $events = Event::with(['grandstands' => function ($queryGrandstand) {
        //     $queryGrandstand->where('is_active', true);
        // }])->where('is_active', true)->get();


        return Inertia::render('sales/index', [
            'salesPending' => $salesPending,
            'events' => $events,
        ]);
    }

    public function events()
    {

        

        $events = Event::where('is_active', true)->with('grandstands')->get();

        return Inertia::render('sales/events/index', ['items' => $events,]);
    }

    //Route::get('/events/{id}', [SalesController::class, 'show'])->name('show');

    public function show($id)
    {


        $event = Event::with(['grandstands' => function ($queryGrandstand) {
            $queryGrandstand->where('is_active', true)->with('seats');
        }])->where('id', $id)->first();


        // $event = Event::with('grandstands.seats')->find($id);

        return Inertia::render('sales/events/show', ['item' => $event]);
    }

    //Route::get('/events/grandstand/{id}', [SalesController::class, 'grandstandSale'])->name('grandstand');

    public function grandstandSale($event,$id)
    {
        // $grandstand = Grandstand::with('seats')->find($id);

        $event = Event::with(['grandstands' => function ($queryGrandstand) use ($id){
            $queryGrandstand->where('is_active', true)->where('id', $id)->with('seats');
        }])->where('id', $event)->first();

        return Inertia::render('sales/events/grandstand', ['item' => $event]);
    }
}
