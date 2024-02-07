<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
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


}
