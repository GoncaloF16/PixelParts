<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

        $products = Product::latest()->take(6)->get();
        return view ('index', compact('products'));

    }

    public function about() {

        return view('about');

    }

    public function searchbar(Request $request)
{
    $query = $request->input('q');

    $produtos = Produto::query()
        ->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('nome', 'like', "%{$query}%")
                         ->orWhere('descricao', 'like', "%{$query}%")
                         ->orWhere('marca', 'like', "%{$query}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    return view('products.products', compact('produtos', 'query'));
}
}
