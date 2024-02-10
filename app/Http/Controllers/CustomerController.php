<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
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


    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->update($request->all());

        return redirect()->back()->with('success', 'Información institucional actualizada correctamente');
    }
}
