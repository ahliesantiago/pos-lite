<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    // Fetch all carts and their corresponding cart_items and product names
    public function index()
    {
        $orders = Cart::with('cart_items.product:id,name')->latest()->get();
        return Inertia::render('History/Orders', [
            'orders' => $orders,
        ]);
    }

    // Store a new cart and its corresponding cart_item
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable',
            'is_paid' => 'required|boolean',
            'is_discounted' => 'required|boolean',
            'total_price' => 'required|numeric',
            'due_date' => 'nullable|date',
            'remarks' => 'nullable',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.stocks' => 'required|integer|numeric',
            'products.*.quantity' => 'required|integer|numeric|min:1',
            'products.*.price_at_checkout' => 'required|numeric',
            'products.*.total_price' => 'required|numeric',
        ]);

        // Check stock availability
        foreach ($validated['products'] as $item) {
            $product = Product::find($item['product_id']);
    
            if (!$product || $product->stocks < $item['quantity']) {
                return back()->withErrors(['error' => "Insufficient stock for {$product->name}."]);
            }
        }

        $cart = Cart::create([
            'customer_name' => $validated['customer_name'],
            'is_paid' => $validated['is_paid'],
            'is_discounted' => $validated['is_discounted'],
            'total_price' => $validated['total_price'],
            'due_date' => $validated['due_date'],
            'remarks' => $validated['remarks'],
        ]);

        foreach ($validated['products'] as $item) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_at_checkout' => $item['price_at_checkout'],
                'total_price' => $item['total_price'],
            ]);

            // Decrement to prevent possible race conditions (i.e. if multiple users check out at the same time)
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->decrement('stocks', $item['quantity']);
            }
        }

        return redirect()->route('orders')->with('success', 'Order logged successfully');
    }

    // Fetch a specific cart's items and their corresponding product names
    // Returns a JSON response
    public function show($order_id)
    {
        $cartItems = CartItem::where('cart_id', $order_id)
            ->with('product:id,product_name')
            ->get();

        return response()->json($cartItems);
    }

    public function update()
    {

    }
}