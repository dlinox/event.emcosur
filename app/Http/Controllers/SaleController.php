<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmMail;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Grandstand;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $customer = Customer::create($request->customer);

            $sale = Sale::create([
                'status' => 'completed',
                'payment_type' => 'cash',
                'payment_method' => 'cash',
                'payment_date' => now(),
                'payment_amount' => $request->total,
                'event_id' => $request->event,
                'customer_id' => $customer->id,
                'user_id' => Auth::user()->id,
            ]);


            $grandstandsIDs = [];

            foreach ($request->seats as $seat) {

                SaleDetail::create([
                    'seat_id' => $seat,
                    'sale_id' => $sale->id,
                ]);
                $seat = Seat::find($seat);

                if (!in_array($seat->grandstand_id, $grandstandsIDs)) {
                    $grandstandsIDs[] = $seat->grandstand_id;
                }
                //validar que no este vendido o reservado
                if ($seat->status != 'available') {
                    throw new \Exception('El asiento ' . $seat->name . ' ya fue vendido o reservado');
                }

                $seat->status = 'sold';
                $seat->save();
            }

            $event = Event::select()->where('id', $request->event)->first();
            $grandstands = Grandstand::select('grandstands.name', DB::raw('GROUP_CONCAT(CONCAT(seats.name ," - S/.", seats.price)) as seats'))
                ->join('seats', 'grandstands.id', '=', 'seats.grandstand_id')
                ->whereIn('grandstands.id', $grandstandsIDs)
                ->whereIn('seats.id', $request->seats)
                ->groupBy('grandstands.id')
                ->get();


            $dataMail = [
                'event' => $event,
                'customer' => $customer,
                'grandstands' => $grandstands,
            ];

            $qrData = encrypt($sale->id);

            $dataMail['qrData'] = $qrData;
            //email email de confirmacion, se envia al cliente, 
            $mail = new ConfirmMail($dataMail);
            Mail::to($customer->email)->send($mail);

            DB::commit();
            return redirect()->back()->with('success', 'Venta realizada con exito');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Se ha producido un error inesperado.', 'details' => $th->getMessage()]);
        }
    }

    public function storeOnline(Request $request)
    {
        DB::beginTransaction();
        try {

            $customer = Customer::create($request->customer);

            $dataSale = [
                'status' => 'pending',
                'payment_type' => 'online',
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'payment_transaction_id' => $request->payment_transaction_id,
                'payment_amount' => $request->payment_amount,
                // 'payment_image' => $request->payment->payment_image,
                'payment_bank' => $request->payment_bank,
                'payment_amount' => $request->total,
                'event_id' => $request->event,
                'customer_id' => $customer->id,

            ];

            if ($request->payment_method === 'card') {

                if (!$request->hasFile('payment_image')) {
                    throw new \Exception('Debe subir la imagen del comprobante de pago');
                }
                $dataSale['payment_image'] = $request->file('payment_image')->store('payments/card', 'public');
            }



            $sale = Sale::create($dataSale);

            foreach ($request->seats as $seat) {

                SaleDetail::create([
                    'seat_id' => $seat,
                    'sale_id' => $sale->id,
                ]);
                $seat = Seat::find($seat);
                //validar que no este vendido o reservado
                if ($seat->status != 'available') {
                    throw new \Exception('El asiento ' . $seat->name . ' ya fue vendido o reservado');
                }

                $seat->status = 'reserved';
                $seat->save();
            }


            DB::commit();
            return redirect()->back()->with('success', 'Su compra se realizo con exito');
        } catch (\Throwable $th) {

            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Se ha producido un error inesperado.', 'details' => $th->getMessage()]);
        }
    }

    public function confirmOnlinePayment($id)
    {

        DB::beginTransaction();
        try {
            $sale = Sale::find($id);
            $sale->status = 'completed';
            $sale->save();

            //bucarcar los detalles de la venta
            $saleDetails = SaleDetail::where('sale_id', $sale->id)->get();

            foreach ($saleDetails as $saleDetail) {
                $seat = Seat::find($saleDetail->seat_id);
                $seat->status = 'sold';
                $seat->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Venta cancelada con exito');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Se ha producido un error inesperado.', 'details' => $th->getMessage()]);
        }
    }

    //cancelar venta
    public function cancel($id)
    {

        DB::beginTransaction();
        try {
            $sale = Sale::find($id);
            $sale->status = 'canceled';
            $sale->save();

            //bucarcar los detalles de la venta
            $saleDetails = SaleDetail::where('sale_id', $sale->id)->get();

            foreach ($saleDetails as $saleDetail) {
                $seat = Seat::find($saleDetail->seat_id);
                $seat->status = 'available';
                $seat->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Venta cancelada con exito');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Se ha producido un error inesperado.', 'details' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
