@extends('layouts.auth')

@section('title', 'Login - PixelParts')
@section('description', 'Acede à tua conta PixelParts para acederes aos melhores componentes gaming')

@section('content')
<div class="animate-fade-up">
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-text-primary mb-2">Bem-vindo de volta</h2>
        <p class="text-text-secondary">Acede à tua conta para continuares a tua jornada gaming</p>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6" id="login-form">
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i data-lucide="mail" class="w-4 h-4"></i>
                Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="form-input @error('email') error @enderror"
                placeholder="o.teu@email.com"
            >
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Password
            </label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="form-input pr-12 @error('password') error @enderror"
                    placeholder="A tua password"
                >
                <button type="button" class="password-toggle" data-target="password">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" class="form-checkbox">
                <span class="text-text-secondary text-sm">Lembrar-me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-brand-green hover:text-brand-blue transition-colors duration-300 text-sm font-medium">
                    Esqueceste a password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="text-white auth-btn-primary">
            <i data-lucide="log-in" class="w-5 h-5"></i>
            Entrar
        </button>

        <!-- Divider -->
        <div class="divider">
            <span>ou</span>
        </div>

        <!-- Social Login (Optional) -->
        <div class="space-y-3">
            <button type="button" class="auth-btn-social">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continuar com Google
            </button>
        </div>
    </form>

    <!-- Register Link -->
    <div class="text-center mt-8 pt-6 border-t border-border">
        <p class="text-text-secondary text-sm">
            Ainda não tens conta?
            <a href="{{ route('register') }}" class="text-brand-green hover:text-brand-blue transition-colors duration-300 font-medium">
                Cria uma agora
            </a>
        </p>
    </div>
</div>
@endsection
