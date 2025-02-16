<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('History/Orders');
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        
    }

    public function show()
    {
        
    }

    public function update()
    {

    }
}