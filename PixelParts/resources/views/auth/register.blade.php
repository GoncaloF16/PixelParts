@extends('layouts.auth')

@section('title', 'Criar Conta - PixelParts')
@section('description', 'Cria a tua conta PixelParts e acede aos melhores componentes gaming do mercado')

@section('content')
<div class="animate-fade-up">
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-text-primary mb-2">Cria a tua conta</h2>
        <p class="text-text-secondary">Junta-te à comunidade gaming PixelParts</p>
    </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-6" id="register-form">
        @csrf

        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="flex items-center gap-2 text-sm font-semibold text-text-primary mb-2">
                <i data-lucide="user" class="w-4 h-4"></i>
                Nome Completo
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                autofocus
                class="w-full px-4 py-3 bg-muted border border-border rounded-lg text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-300 @error('name') border-destructive ring-destructive/10 @enderror"
                placeholder="O teu nome"
            >
            @error('name')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-6">
            <label for="email" class="flex items-center gap-2 text-sm font-semibold text-text-primary mb-2">
                <i data-lucide="mail" class="w-4 h-4"></i>
                Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="w-full px-4 py-3 bg-muted border border-border rounded-lg text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-300 @error('email') border-destructive ring-destructive/10 @enderror"
                placeholder="o.teu@email.com"
            >
            @error('email')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="flex items-center gap-2 text-sm font-semibold text-text-primary mb-2">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Password
            </label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-3 pr-12 bg-muted border border-border rounded-lg text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-300 @error('password') border-destructive ring-destructive/10 @enderror"
                    placeholder="Mínimo 8 caracteres"
                >
                <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-text-secondary hover:text-brand-green transition-colors duration-300 password-toggle" data-target="password">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="flex items-center gap-2 text-sm font-semibold text-text-primary mb-2">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Confirmar Password
            </label>
            <div class="relative">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full px-4 py-3 pr-12 bg-muted border border-border rounded-lg text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-300 @error('password_confirmation') border-destructive ring-destructive/10 @enderror"
                    placeholder="Confirma a tua password"
                >
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password_confirmation')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Strength Indicator -->
        <div class="password-strength mb-6" id="password-strength">
            <div class="strength-bar">
                <div class="strength-fill" id="strength-fill"></div>
            </div>
            <p class="strength-text text-xs text-text-secondary mt-1" id="strength-text">Força da password</p>
        </div>

        <!-- Terms and Privacy -->
        <div class="mb-6">
            <label class="flex items-start space-x-3 cursor-pointer group">
                <input type="checkbox" name="terms" required class="w-4 h-4 mt-1 bg-muted border border-border rounded transition-all duration-300 checked:bg-brand-green checked:border-brand-green">
                <span class="text-text-secondary texing-relaxed group-hover:text-text-primary transition-colors duration-300">
                    Aceito os
                    <a href="#" class="text-brand-green hover:text-brand-blue transition-colors duration-300 hover:underline">Termos de Serviço</a>
                    e
                    <a href="#" class="text-brand-green hover:text-brand-blue transition-colors duration-300 hover:underline">Política de Privacidade</a>
                </span>
            </label>
            @error('terms')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="text-white w-full py-3.5 px-6 bg-gradient-to-r from-brand-green to-brand-blue text-surface-dark font-bold text-sm rounded-lg hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-green/30 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-2">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            Criar Conta
        </button>

        <!-- Divider -->
        <div class="relative text-center my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-border"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="bg-card px-4 text-text-secondary">ou</span>
            </div>
        </div>

        <!-- Social Register (Optional) -->
        <div class="space-y-3">
            <a href="{{ route('login.google') }}">
            <button type="button" class="w-full py-3.5 px-6 bg-muted border border-border text-text-primary font-semibold text-sm rounded-lg hover:bg-card hover:border-brand-green hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Registar com Google
            </button>
            </a>
        </div>
    </form>

    <!-- Login Link -->
    <div class="text-center mt-8 pt-6 border-t border-border">
        <p class="text-text-secondary text-sm">
            Já tens conta?
            <a href="{{ route('login') }}" class="text-brand-green hover:text-brand-blue transition-colors duration-300 font-medium hover:underline">
                Faz login aqui
            </a>
        </p>
    </div>
</div>
@endsection
