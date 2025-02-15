<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProductType;

class ProductTypeController extends Controller
{
    public function list()
    {
        $categories = ProductType::take(10)->get()->toArray();
        return response()->json($categories);
    }

    public function show()
    {

    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_name' => 'required',
        ]);

        ProductType::create($validated);
        return redirect()->route('inventory')->with('success', 'Product category added successfully');
    }

    public function bulkCreate()
    {
        return Inertia::render('Store/BulkAddTypes');
    }
}
