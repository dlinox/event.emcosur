<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SaleDetail;
use App\Models\Seat;
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


        $hash = $id;
        //desencriptar hash
        $decrypted = decrypt($hash);

        try {
            $saleDetail = SaleDetail::select(
                'events.name as eventName',
                'grandstands.id as grandstandId ',
                'grandstands.name as grandstandName',
                'seats.name as seatName',
                'seats.id as seatId',
                'seats.price as seatPrice',
                'seats.is_used as seatIsUsed',
                'sales.status as saleStatus',
                'sales.id as saleId',
                'sales.customer_id as customerId',
            )
                ->join('seats', 'seats.id', '=', 'sale_details.seat_id')
                ->join('grandstands', 'grandstands.id', '=', 'seats.grandstand_id')
                ->join('events', 'events.id', '=', 'grandstands.event_id')
                ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
                ->where('sales.id', $decrypted)
                ->get();

            $customer = Customer::select(
                'name',
                'last_name',
                'document_type',
                'document_number',
                'email',
                'phone'
            )->where('id', $saleDetail[0]->customerId)->first();

            if ($saleDetail) {
                return response()->json([
                    'success' => 'ok',
                    'data' => [
                        'saleDetail' => $saleDetail,
                        'customer' => $customer
                    ],

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
    //marcar asiento como usado, puden ser varios asientos
    public function markAsUsed(Request $request)
    {
        //id,id
        $ids =  $request->ids;
        try {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $seat = Seat::find($id);
                $seat->is_used = true;
                $seat->save();
            }
            DB::commit();
            return response()->json([
                'success' => 'ok',
                'message' => 'Asientos marcados como usados'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => 'error',
                'error' => $th->getMessage(),
                'ids' => $ids
            ]);
        }
    }
}
