<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Grandstand;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $events = Event::all();
        return Inertia::render('admin/events/index', [
            'items' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/events/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            DB::beginTransaction();
            $event = Event::create([
                'name' => $request->name,
                'description' => $request->description,
                'location_reference' => $request->location_reference,
                'ubication' => $request->ubication,
                //'image' => $request->image,
                'date' => $request->date,
            ]);

            foreach ($request->grandstands as $grandstand_) {
                $grandstand = Grandstand::create([
                    'name' => $grandstand_['name'],
                    'capacity' => $grandstand_['capacity'],
                    'rows' => $grandstand_['rows']['capacity'],
                    'event_id' => $event->id,
                ]);

                foreach ($grandstand_['rows']['seats'] as $seat) {


                    for ($i  = 1; $i <= $seat['capacity']; $i++) {
                        Seat::create([
                            'name' => $seat['row'] . $i,
                            'row'  => $seat['row'],
                            'number' => $i,
                            'price' => $seat['price'],
                            'grandstand_id' => $grandstand->id
                        ]);
                    }
                }
            }
            DB::commit();

            return redirect('/a/events')->with('success', "Evento creado satisfactoriamente");
        } catch (\Throwable $th) {

            return back()->with([
                "errors" => $th->getMessage(),
            ]);
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
