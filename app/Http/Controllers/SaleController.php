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
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SaleController extends Controller
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
            // $query->where('title', 'like', '%' . $searchTerm . '%');
        }
        $query->with('event', 'customer');
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
                'status' => $request->option === 'sold' ? 'completed' : 'pending',
                'payment_type' => 'cash',
                'payment_method' => 'cash',
                'payment_date' => now(),
                'payment_amount' => $request->payment_amount,
                'event_id' => $request->event,
                'customer_id' => $customer->id,
                'observation' => $request->observation,
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

                $seat->status = $request->option === 'sold' ? 'sold' : 'reserved';
                $seat->save();
            }

            $event = Event::select()->where('id', $request->event)->first();
            $grandstands = Grandstand::select('grandstands.name', DB::raw('GROUP_CONCAT(seats.name) as seats'))
                ->join('seats', 'grandstands.id', '=', 'seats.grandstand_id')
                ->whereIn('grandstands.id', $grandstandsIDs)
                ->whereIn('seats.id', $request->seats)
                ->groupBy('grandstands.id')
                ->get();

            $dataMail = [
                'event' => $event,
                'customer' => $customer,
                'grandstands' => $grandstands,
                'hash' => encrypt($sale->id),
            ];

            $mail = new ConfirmMail($dataMail);
            Mail::to([$customer->email, 'kf.emcosur@gmail.com'])->send($mail);

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

                $seat->status = 'reserved';
                $seat->save();
            }

            $event = Event::select()->where('id', $request->event)->first();
            $grandstands = Grandstand::select('grandstands.name', DB::raw('GROUP_CONCAT(seats.name) as seats'))
                ->join('seats', 'grandstands.id', '=', 'seats.grandstand_id')
                ->whereIn('grandstands.id', $grandstandsIDs)
                ->whereIn('seats.id', $request->seats)
                ->groupBy('grandstands.id')
                ->get();

            $dataMail = [
                'event' => $event,
                'customer' => $customer,
                'grandstands' => $grandstands,
                'hash' => encrypt($sale->id),
            ];

            $mail = new ConfirmMail($dataMail);
            //con copia 
            Mail::to([$customer->email, 'kf.emcosur@gmail.com'])->send($mail);
            // Mail::to([$customer->email, ])->send($mail);

            DB::commit();
            return redirect()->back()->with('success', '¡Gracias por tu compra! la estamos procesando, en breve le lleagará un correo con los detalles de su compra, no olvides revisar tu bandeja de spam.');
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

            var_dump($sale);
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
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
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


    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            //consideras los foreign key
            $sale = Sale::find($id);

            //eliminar los detalles de la venta
            $saleDetails = SaleDetail::where('sale_id', $sale->id)->get();

            foreach ($saleDetails as $saleDetail) {
                //cambiar el estado del asiento
                $seat = Seat::find($saleDetail->seat_id);
                $seat->status = 'available';
                $seat->save();
                //eliminar el detalle
                $saleDetail->delete();
            }


            $sale->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Venta eliminada con exito');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Se ha producido un error inesperado.', 'details' => $th->getMessage()]);
        }
    }

    public function sendEmail($id)
    {
        try {
            $sale = Sale::find($id);

            $saleDetail = SaleDetail::where('sale_id', $sale->id)->first();

            $customer = Customer::find($sale->customer_id);

            $grandstands = Grandstand::select('grandstands.name', DB::raw('GROUP_CONCAT(seats.name) as seats'))
                ->join('seats', 'grandstands.id', '=', 'seats.grandstand_id')
                ->where('seats.id', $saleDetail->seat_id)
                ->groupBy('grandstands.id')
                ->get();

            $event = Event::select()->where('id', $sale->event_id)->first();

            $dataMail = [
                'event' => $event,
                'customer' => $customer,
                'grandstands' => $grandstands,
                'hash' => encrypt($sale->id),
            ];

            $mail = new ConfirmMail($dataMail);
            Mail::to([$customer->email, 'kf.emcosur@gmail.com'])->send($mail);
            return redirect()->back()->with('success', 'Correo enviado con exito');
        } catch (\Throwable $th) {

            return redirect()->back()->withErrors(['error' => 'Se ha producido un error inesperado.', 'details' => $th->getMessage()]);
        }
    }
}
