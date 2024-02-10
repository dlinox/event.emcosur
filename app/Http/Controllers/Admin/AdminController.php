<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Grandstand;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Seat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{

    protected $sale;
    public function __construct()
    {
        $this->sale = new Sale();
    }

    public function index(Request $request)
    {

        $perPage = $request->input('perPage', 10);
        $query = Sale::query();

        if ($request->has('search')) {
            $searchTerm = $request->search;

            //buscar por usuario y cliente
            $query->whereHas('user', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })->orWhereHas('customer', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
            // $query->where('title', 'like', '%' . $searchTerm . '%');
        }
        $query->with('event', 'customer', 'user', 'saleDetails');
        // if (auth()->user()->role !== 'Administrador') {
        //     $query->where('author_id', auth()->user()->id);
        // }
        // Obtener resultados paginados
        $items = $query->paginate($perPage)->appends($request->query());

        return Inertia::render('admin/index', [
            'title' => 'Eventos  y campañas',
            'subtitle' => 'Gestiona los eventos y campañas de la página',
            'items' => $items,
            'headers' => $this->sale->headers,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function reportEvent()
    {
        $events = Event::select('id', 'name')->where('is_active', true)->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'grandstands' => Grandstand::select('id', 'name')->where('event_id', $event->id)->get()->map(function ($grandstand) use ($event) {
                    return [
                        'id' => $grandstand->id,
                        'name' => $grandstand->name,
                        'seatsSold' => SaleDetail::join('seats', 'sale_details.seat_id', '=', 'seats.id')
                            ->join('grandstands', 'seats.grandstand_id', '=', 'grandstands.id')
                            ->where('grandstands.id', $grandstand->id)
                            ->where('grandstands.event_id', $event->id)
                            ->where('seats.status', 'sold')
                            ->count(),

                        'seatsSold2' => Seat::where('grandstand_id', $grandstand->id)
                            ->where('status', '=', 'sold')
                            ->count(),

                        'seatsReserved' => Seat::where('grandstand_id', $grandstand->id)
                            ->where('status', '=', 'reserved')
                            ->count(),

                        'seatsAvailable'  => Seat::where('grandstand_id', $grandstand->id)
                            ->where('status', '=', 'available')
                            ->count(),


                        'totalSeats' => Seat::where('grandstand_id', $grandstand->id)
                            ->where('status', '!=', 'displacement')
                            ->count(),

                    ];
                }),
            ];
        });

        return response()->json($events);
    }
}
