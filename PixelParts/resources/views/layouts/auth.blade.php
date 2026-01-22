<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PixelParts - Gaming Components')</title>
    <meta name="description" content="@yield('description', 'Sistema de autenticação PixelParts')">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: 'hsl(222 17% 8%)',
                        foreground: 'hsl(220 15% 95%)',
                        card: 'hsl(222 17% 12%)',
                        border: 'hsl(215 28% 17%)',
                        input: 'hsl(215 28% 17%)',
                        primary: 'hsl(142 76% 36%)',
                        secondary: 'hsl(217 32% 17%)',
                        muted: 'hsl(215 28% 17%)',
                        accent: 'hsl(215 28% 17%)',
                        destructive: 'hsl(0 84% 60%)',
                        'brand-green': 'hsl(142 76% 36%)',
                        'brand-blue': 'hsl(217 91% 60%)',
                        'brand-purple': 'hsl(271 81% 56%)',
                        'surface-dark': 'hsl(220 15% 5%)',
                        'text-primary': 'hsl(220 15% 95%)',
                        'text-secondary': 'hsl(220 9% 46%)',
                    },
                    animation: {
                        'float-particle': 'float-particle 8s infinite linear',
                        'fade-up': 'fade-up 0.6s ease-out forwards',
                    },
                    keyframes: {
                        'float-particle': {
                            '0%, 100%': { transform: 'translateY(0) translateX(0) rotate(0deg)', opacity: '0' },
                            '10%': { opacity: '1' },
                            '90%': { opacity: '1' },
                            '50%': { transform: 'translateY(-80px) translateX(30px) rotate(180deg)', opacity: '0.6' },
                        },
                        'fade-up': {
                            'from': { opacity: '0', transform: 'translateY(20px)' },
                            'to': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen bg-background">
    <div class="min-h-screen flex">
        <!-- Left Side - Brand/Hero -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-brand-green/20 via-brand-blue/20 to-brand-purple/20"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-brand-green/10 via-transparent to-brand-blue/10"></div>

            <!-- Particles -->
            <div class="absolute inset-0 pointer-events-none">
                @for ($i = 1; $i <= 8; $i++)
                    <div class="absolute w-1 h-1 bg-brand-green rounded-full opacity-60 animate-float-particle"
                         style="left: {{ rand(10, 90) }}%; top: {{ rand(10, 90) }}%; animation-delay: {{ $i * 0.5 }}s;"></div>
                @endfor
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center text-center p-12">
                <!-- Logo -->
                <div class="flex items-center space-x-3 mb-8 transition-all duration-300 hover:scale-105 hover:drop-shadow-2xl">
                    <img src="{{ asset('images/PixelParts.png') }}" alt="PixelParts Logo" class="h-[80px] w-[100px]">
                    <span class="text-4xl font-bold bg-gradient-to-r from-brand-green to-brand-blue bg-clip-text text-transparent">PixelParts</span>
                </div>

                <!-- Hero Text -->
                <h1 class="text-4xl font-bold text-text-primary mb-6 max-w-md">
                    Componentes Gaming de <span class="bg-gradient-to-r from-brand-green to-brand-blue bg-clip-text text-transparent">Elite</span>
                </h1>
                <p class="text-text-secondary text-lg max-w-md leading-relaxed">
                    Eleva o teu setup gaming com os melhores componentes do mercado.
                    Performance extrema, qualidade premium.
                </p>

                <!-- Features -->
                <div class="mt-12 space-y-4 text-left">
                    <div class="flex items-center gap-3 group">
                        <div class="w-8 h-8 bg-gradient-to-r from-brand-green to-brand-blue rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="zap" class="w-4 h-4 text-surface-dark"></i>
                        </div>
                        <span class="text-text-secondary group-hover:text-text-primary transition-colors duration-300">Performance Extrema</span>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <div class="w-8 h-8 bg-gradient-to-r from-brand-blue to-brand-purple rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="shield-check" class="w-4 h-4 text-surface-dark"></i>
                        </div>
                        <span class="text-text-secondary group-hover:text-text-primary transition-colors duration-300">Qualidade Garantida</span>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <div class="w-8 h-8 bg-gradient-to-r from-brand-purple to-brand-green rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="truck" class="w-4 h-4 text-surface-dark"></i>
                        </div>
                        <span class="text-text-secondary group-hover:text-text-primary transition-colors duration-300">Entrega Rápida</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Auth Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-6">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center mb-6 sm:mb-8 transition-all duration-300 hover:scale-105">
                    <img src="{{ asset('images/PixelParts.png') }}" alt="PixelParts Logo" class="h-[40px] w-[60px] sm:h-[50px] sm:w-[70px]">
                </div>

                <!-- Auth Form Container -->
                <div class="bg-card p-4 sm:p-6 md:p-8 rounded-xl border border-border shadow-2xl hover:shadow-3xl hover:bg-muted/50 transition-all duration-300">
                    @yield('content')
                </div>

                <!-- Footer Links -->
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-text-secondary hover:text-brand-green transition-colors duration-300 text-sm hover:underline">
                        ← Voltar à página inicial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
