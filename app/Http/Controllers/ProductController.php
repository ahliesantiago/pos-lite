<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    // Renders the Inventory page which displays the top products and allows adding to cart (and checkout)
    public function index()
    {
        return Inertia::render('Store/Inventory');
    }

    /**
     * Fetches the list of products to be displayed on the Inventory page
     * Returns a JSON response of the products
     */
    public function list()
    {
        $products = Product::where('is_archived', 0)->orderBy('product_name')->with('productType')->get();
        return response()->json($products);
    }

    /**
     * Shows either the most popular products (based on instances on the existing carts),
     * and/or by the most recently added products.
     */
    public function featured($itemCount = 10)
    {
        $topProducts = Product::select('products.*')
            ->leftJoin('cart_items', 'products.id', '=', 'cart_items.product_id')
            ->where('cart_items.created_at', '>=', now()->subDays(30))
            ->where('is_archived', 0)
            ->groupBy('products.id')
            ->orderByRaw('COUNT(cart_items.id) DESC') // Order by most added to cart
            ->take($itemCount)
            ->get()->toArray();

        if (count($topProducts) < $itemCount) {
            $remainingCount = $itemCount - count($topProducts);
    
            $latestProducts = Product::latest()->take($remainingCount)->get()->toArray();
    
            // Merge popular and latest products
            $products = array_merge($topProducts, $latestProducts);
        } else {
            $products = $topProducts;
        }

        return response()->json($products);
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
        return back();
    }

    public function update(Request $request, $product_id)
    {
        $validated = request()->validate([
            'product_name' => 'required|unique:products,product_name,' . $product_id,
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

        Product::find($product_id)->update($validated);
        return back();
    }

    public function archive(Product $product)
    {
        $product->update(['is_archived' => true]);
        return back();
    }

    public function bulkCreate()
    {
        return Inertia::render('Store/BulkAddProducts');
    }
}