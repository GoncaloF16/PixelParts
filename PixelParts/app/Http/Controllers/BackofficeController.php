<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BackofficeController extends Controller
{
    public function index() {
        $id = Auth::id();

        $user = DB::table('users')
            ->select('name', 'email')
            ->where('id', $id)
            ->first();

        return view('backoffice.dashboard', compact('user'));

    }
}
