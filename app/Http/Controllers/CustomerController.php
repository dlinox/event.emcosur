<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerController extends Controller
{


    protected $customer;

    public function __construct()
    {
        $this->customer = new Customer();
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = Customer::query();

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('document_number', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
        }


        $items = $query->paginate($perPage)->appends($request->query());

        return Inertia::render('admin/customers/index', [
            'items' => $items,
            'headers' => $this->customer->headers,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->update($request->all());

        return redirect()->back()->with('success', 'Información institucional actualizada correctamente');
    }

    public function searchCustomer(Request $request)
    {
        $searchTerm = $request->search;
        $customers = Customer::select(
            'customers.id',
            'customers.name',
            'customers.last_name',
            'customers.document_number',
            'customers.email',
            'customers.phone',
            'sales.id as sale_id',
            'sales.status as sale_status',
            'events.name as event_name',
            DB::raw('COUNT(sale_details.id) as total_sales'),
        )
            ->join('sales', 'customers.id', '=', 'sales.customer_id')
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('events', 'sales.event_id', '=', 'events.id')
            ->where('customers.name', 'LIKE', "%{$searchTerm}%")
            // ->orWhere('customers.last_name', 'LIKE', "%{$searchTerm}%")
            // ->orWhere('customers.document_number', 'LIKE', "%{$searchTerm}%")
            // ->orWhere('customers.email', 'LIKE', "%{$searchTerm}%")
            // ->orWhere('customers.phone', 'LIKE', "%{$searchTerm}%")
            ->groupBy('customers.id', 'sales.id', 'events.name')
            ->limit(20)
            ->get()->map(function ($customer) {
                $customer->hash = encrypt($customer->sale_id);
                // $customer->hash = Encrypter::encryptString($customer->sale_id);
                return $customer;
            });


        return response()->json($customers);
    }
}
