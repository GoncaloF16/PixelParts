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
    /**
     * Redireciona para a página de login do Google
     */
   public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}


    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Verifica se o usuário já existe no banco pelo email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Se não existe, cria
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Str::random(16), // senha aleatória
                'role' => 'user',
            ]);
        }

        // Faz login com o usuário da base de dados
        Auth::login($user);

        return redirect()->intended('home');

    } catch (\Exception $e) {
    // Regista no log do Laravel
    \Log::error('Erro no login Google: '.$e->getMessage());

    // Retorna uma resposta amigável
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
