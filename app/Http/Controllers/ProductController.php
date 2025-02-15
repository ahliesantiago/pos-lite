<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

class ProductController extends Controller
{
    // Fetch and show all products
    public function index()
    {
        return Inertia::render('Inventory/AllProducts');
    }

    public function show()
    {

    }

    public function create()
    {
        
    }

    public function store()
    {
        
    }

    public function bulkCreate()
    {
        return Inertia::render('Inventory/BulkAddProducts');
    }
}