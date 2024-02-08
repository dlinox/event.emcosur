<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Grandstand;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ControlController extends Controller
{
    public function index()
    {

        return Inertia::render('control/index');
    }

    public function showSaleDetails($id)
    {

        try {
            $saleDetail = SaleDetail::select(
                'events.name as eventName',
                'grandstands.id as grandstandId ',
                'grandstands.name as grandstandName',
                'seats.name as seatName',
                'seats.id as seatId',
                'seats.price as seatPrice',
                'sales.status as saleStatus',
                'sales.id as saleId',
            )
                ->join('seats', 'seats.id', '=', 'sale_details.seat_id')
                ->join('grandstands', 'grandstands.id', '=', 'seats.grandstand_id')
                ->join('events', 'events.id', '=', 'grandstands.event_id')
                ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
                ->where('sales.id', $id)
                ->get();

            if ($saleDetail) {
                return response()->json([
                    'success' => 'ok',
                    'data' => $saleDetail,
                    'id' => $id
                ]);
            }

            return response()->json([
                'succsess' => 'ok',
                'data' => null

            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => 'error',
                'error' => $th->getMessage(),
                'id' => $id
            ]);
        }
    }
}
