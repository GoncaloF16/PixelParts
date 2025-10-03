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
}
