<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProductType;

class ProductTypeController extends Controller
{
    public function list()
    {
        $categories = ProductType::with('parentType')->withCount('products')->get();
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
            'type_name' => 'required|unique:product_types,type_name',
            'parent_type_id' => [
                'nullable',
                'exists:product_types,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value == $request->id) {
                        $fail('Product type cannot reference itself as a parent type');
                    }
                }
            ],
            'description' => 'nullable',
        ]);

        ProductType::create($validated);
        return redirect()->route('inventory')->with('success', 'Product category added successfully');
    }

    public function bulkCreate()
    {
        return Inertia::render('Store/BulkAddTypes');
    }
}
