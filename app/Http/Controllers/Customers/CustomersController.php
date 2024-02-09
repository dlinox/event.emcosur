<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Grandstand;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class CustomersController extends Controller
{
    public function index()
    {

        $vents = Event::where('is_active', 1)->with('grandstands')->get();
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

    public function ticket(Request $request)
    {

        $hash = $request->hash;
        //desencriptar hash
        $decrypted = decrypt($hash);

        $saleDetail = SaleDetail::select(
            'events.name as eventName',
            'grandstands.id as grandstandId ',
            'grandstands.name as grandstandName',
            'seats.name as seatName',
            'seats.id as seatId',
            'seats.is_used as seatIsUsed',
            'seats.price as seatPrice',
            'sales.status as saleStatus',
            'sales.id as saleId',
        )
            ->join('seats', 'seats.id', '=', 'sale_details.seat_id')
            ->join('grandstands', 'grandstands.id', '=', 'seats.grandstand_id')
            ->join('events', 'events.id', '=', 'grandstands.event_id')
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->where('sales.id', $decrypted)
            ->get();

        
        
        return Inertia::render('customers/ticket', [
            'hash' => $hash,
            'saleDetail' => $saleDetail,

        ]);
    }
}
