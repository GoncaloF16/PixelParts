<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerForm() {
        return view('auth.register');
    }

    public function registerUser(Request $request) {
        $request -> validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:users|email|max:35',
            'password' => 'required|min:8',
        ]);

        User::insert([
            'name' => $request -> name,
            'email' => $request -> email,
            'password' => Hash::make($request -> password)
        ]);

        return redirect() -> route('login') -> with('success', 'Registration successful. Please login.');
    }
}


