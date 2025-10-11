<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{

   public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}


    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Str::random(16),
                'role' => 'user',
            ]);
        }

        Auth::login($user);

        return redirect()->intended('home');

    } catch (\Exception $e) {
    \Log::error('Erro no login Google: '.$e->getMessage());

    return response()->json([
        'status' => 'erro',
        'mensagem' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'stack' => $e->getTraceAsString()
    ], 500);
}

}

}
