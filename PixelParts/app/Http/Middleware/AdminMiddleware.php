<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Se não estiver autenticado ou não for admin
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('fallback');
        }
    

        // Caso seja admin, continua
        return $next($request);
    }
}
