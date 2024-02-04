<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Grandstand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomersController extends Controller
{
    public function index()
    {

        $vents = Event::with('grandstands')->get();
        return Inertia::render('customers/index', [
            'events' => $vents,
        ]);
    }

    public function sale($id)
    {
        $grandstands = Grandstand::select('event_id')->where('id', $id)->first();

        $event = Event::with(['grandstands' => function ($queryGrandstand) use ($id) {
            $queryGrandstand->where('id', $id);
        
            $queryGrandstand->with(['seats' => function ($querySeat) {
  
                $querySeat->where('is_active', true);
            }]);
        }])->where('id', $grandstands->event_id)->first();


        // $event = Event::with('grandstands.seats')->where('id', $grandstands->event_id)->first();

        return Inertia::render('customers/sale', [
            'item' => $event,
            'grandstandId' => $id,
        ]);
    }

    public function payment()
    {
        return Inertia::render('customers/payment');
    }
}
