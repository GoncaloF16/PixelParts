<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdsController extends Controller
{
    public function details() {

        return view('products.details');

    }
}
