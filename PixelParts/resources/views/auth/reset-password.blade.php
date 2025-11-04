@extends('layouts.auth')

@section('title', 'Redefinir Password - PixelParts')
@section('description', 'Cria uma nova password para a tua conta PixelParts')

@section('content')
<div class="animate-fade-up">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-brand-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="shield-check" class="w-8 h-8 text-brand-green"></i>
        </div>
        <h2 class="text-3xl font-bold text-text-primary mb-2">Redefinir Password</h2>
        <p class="text-text-secondary">Cria uma nova password segura para a tua conta</p>
    </div>

    <!-- Reset Password Form -->
    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                class="form-input @error('email') error @enderror"
                placeholder="o.teu@email.com"
            >
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- New Password -->
        <div class="form-group">
            <label for="password" class="form-label">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Nova Password
            </label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="form-input pr-12 @error('password') error @enderror"
                    placeholder="Mínimo 8 caracteres"
                >
                <button type="button" class="password-toggle" data-target="password">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Confirmar Nova Password
            </label>
            <div class="relative">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    class="form-input pr-12 @error('password_confirmation') error @enderror"
                    placeholder="Repete a password"
                >
                <button type="button" class="password-toggle" data-target="password_confirmation">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password_confirmation')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Requirements -->
        <div class="bg-surface/50 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-brand-blue mt-0.5"></i>
                <div>
                    <h4 class="text-text-primary font-medium text-sm mb-2">Requisitos da Password:</h4>
                    <ul class="text-text-secondary text-sm space-y-1">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-3 h-3 text-brand-green"></i>
                            Mínimo de 8 caracteres
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-3 h-3 text-brand-green"></i>
                            Recomendado: letras maiúsculas e minúsculas
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-3 h-3 text-brand-green"></i>
                            Recomendado: números e caracteres especiais
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="text-white auth-btn-primary">
            <i data-lucide="shield-check" class="w-5 h-5"></i>
            Redefinir Password
        </button>
    </form>

    <!-- Back to Login -->
    <div class="text-center mt-8 pt-6 border-t border-border">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-brand-green hover:text-brand-blue transition-colors duration-300 text-sm font-medium">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Voltar ao Login
        </a>
    </div>
</div>
@endsection
