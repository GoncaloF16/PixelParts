<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilsController extends Controller
{
    public function fallback() {

        return view ('errors.fallback');

    }
}
