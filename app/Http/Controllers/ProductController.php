<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Store/Inventory');
    }

    public function list()
    {
        $products = Product::take(10)->get()->toArray();
        return response()->json($products);
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
            'product_name' => 'required|unique:products,product_name',
            'product_type_id' => 'required|exists:product_types,id',
            'brand' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'discounted_price_1' => 'nullable|numeric',
            'discounted_price_2' => 'nullable|numeric',
            'discounted_price_3' => 'nullable|numeric',
            'purchase_wholesale_price' => 'nullable|numeric',
            'stocks' => 'required|integer|numeric|min:0',
            'closest_expiration_date' => 'nullable|date',
        ]);

        Product::create($validated);
        return redirect()->route('dashboard')->with('success', 'Product added successfully');
    }

    public function bulkCreate()
    {
        return Inertia::render('Store/BulkAddProducts');
    }
}