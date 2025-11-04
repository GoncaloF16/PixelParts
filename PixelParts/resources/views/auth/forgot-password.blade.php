@extends('layouts.auth')

@section('title', 'Recuperar Password - PixelParts')
@section('description', 'Recupera o acesso à tua conta PixelParts')

@section('content')
<div class="animate-fade-up">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-brand-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="key" class="w-8 h-8 text-brand-green"></i>
        </div>
        <h2 class="text-3xl font-bold text-text-primary mb-2">Esqueceste a password?</h2>
        <p class="text-text-secondary">Não te preocupes! Introduz o teu email e enviaremos um link para recuperares a tua password.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <p class="text-green-500 text-sm">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <!-- Forgot Password Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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

        <!-- Submit Button -->
        <button type="submit" class="text-white auth-btn-primary">
            <i data-lucide="send" class="w-5 h-5"></i>
            Enviar Link de Recuperação
        </button>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-brand-green hover:text-brand-blue transition-colors duration-300 text-sm font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Voltar ao Login
            </a>
        </div>
    </form>

    <!-- Help Text -->
    <div class="mt-8 pt-6 border-t border-border">
        <div class="bg-surface/50 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-brand-blue mt-0.5"></i>
                <div>
                    <h4 class="text-text-primary font-medium text-sm mb-1">Como funciona?</h4>
                    <p class="text-text-secondary text-sm">
                        Vais receber um email com um link seguro para criares uma nova password.
                        O link é válido apenas por 60 minutos por questões de segurança.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Link -->
    <div class="text-center mt-6">
        <p class="text-text-secondary text-sm">
            Ainda não tens conta?
            <a href="{{ route('register') }}" class="text-brand-green hover:text-brand-blue transition-colors duration-300 font-medium">
                Cria uma agora
            </a>
        </p>
    </div>
</div>
@endsection
