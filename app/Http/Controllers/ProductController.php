<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_in_cents' => 'required|integer|min:1',
            'cost_in_cents' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        return redirect()->route('products.index')
                         ->with('success', 'Produto criado com sucesso!');
    }
}
