<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProdsController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::orderBy('name')->get();

        return view('products.products', compact('products', 'categories'));

    }


    public function show($slug)
    {
        $product = Product::with([
            'category',
            'specifications',
            'features',
            'compatibility',
            'reviews.user'
        ])->where('slug', $slug)->firstOrFail();

        $averageRating = $product->averageRating();

        return view('products.details', [
            'product' => $product,
            'averageRating' => $averageRating
        ]);
    }
}
