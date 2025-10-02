<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProdsController extends Controller
{
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
