@extends('layouts.master')

@section('content')
<main class="bg-surface text-text-primary">
    <!-- Sobre a PixelParts -->
    <section class="pt-1 py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-surface-dark via-surface to-surface-dark opacity-90"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-5xl mx-auto">
                <!-- História -->
                <div class="grid md:grid-cols-2 gap-12 items-center mb-24 animate-fade-up">
                    <div>
                        <h2 class="text-4xl font-bold text-text-primary mb-6">A Nossa História</h2>
                        <p class="text-text-secondary mb-4 leading-relaxed">
                            Fundada em 2025, a PixelParts nasceu da paixão pela tecnologia e do desejo de oferecer aos
                            entusiastas de PC em Portugal acesso aos melhores componentes do mercado.
                        </p>
                        <p class="text-text-secondary mb-4 leading-relaxed">
                            Com uma equipa especializada e dedicada, rapidamente nos tornámos uma referência no sector,
                            conhecidos pela nossa vasta gama de produtos, preços competitivos e atendimento ao cliente
                            excecional.
                        </p>
                        <p class="text-text-secondary leading-relaxed">
                            Hoje, servimos milhares de clientes em todo o país, desde gamers apaixonados a profissionais
                            que procuram o melhor desempenho para o seu trabalho.
                        </p>
                    </div>

                    <div class="rounded-2xl overflow-hidden shadow-xl h-64">
                        <img src="https://plus.unsplash.com/premium_photo-1683121716061-3faddf4dc504?auto=format&fit=crop&q=80&w=1332"
                            alt="Chip de computador" class="w-full h-full object-cover" />
                    </div>
                </div>

                <!-- Missão, Visão, Valores -->
                <div class="grid md:grid-cols-3 gap-8 mb-24 animate-fade-up">
                    <div
                        class="bg-surface-card p-8 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform duration-300">
                        <div
                            class="w-14 h-14 bg-gradient-to-r from-brand-green to-brand-blue rounded-lg flex items-center justify-center mb-6">
                            <i data-lucide="rocket" class="w-7 h-7 text-surface-dark"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Missão</h3>
                        <p class="text-text-secondary">
                            Fornecer componentes de PC de alta qualidade com preços competitivos e um serviço ao cliente
                            incomparável.
                        </p>
                    </div>

                    <div
                        class="bg-surface-card p-8 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform duration-300">
                        <div
                            class="w-14 h-14 bg-gradient-to-r from-brand-green to-brand-blue rounded-lg flex items-center justify-center mb-6">
                            <i data-lucide="eye" class="w-7 h-7 text-surface-dark"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Visão</h3>
                        <p class="text-text-secondary">
                            Ser a loja de referência em Portugal para todos os entusiastas de tecnologia e construção de
                            PCs.
                        </p>
                    </div>

                    <div
                        class="bg-surface-card p-8 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform duration-300">
                        <div
                            class="w-14 h-14 bg-gradient-to-r from-brand-green to-brand-blue rounded-lg flex items-center justify-center mb-6">
                            <i data-lucide="heart" class="w-7 h-7 text-surface-dark"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Valores</h3>
                        <p class="text-text-secondary">
                            Qualidade, transparência, inovação e compromisso total com a satisfação dos nossos clientes.
                        </p>
                    </div>
                </div>

                <!-- Porquê Escolher -->
                <div
                    class="bg-surface-card rounded-2xl p-12 mb-24 animate-fade-up shadow-lg hover:shadow-xl transition-all duration-500">
                    <h2 class="text-4xl font-bold text-white mb-12 text-center">
                        Porquê Escolher a <span class="text-gradient-brand">PixelParts?</span>
                    </h2>
                    <div class="grid md:grid-cols-2 gap-8">
                        @foreach ([
                            ['Produtos Originais', 'Todos os nossos produtos são originais e vêm com garantia oficial.'],
                            ['Entregas Rápidas', 'Envios em 24-48h para todo o país com tracking.'],
                            ['Apoio Especializado', 'Equipa técnica pronta para ajudar na escolha dos componentes.'],
                            ['Melhores Preços', 'Preços competitivos e promoções regulares.']
                        ] as [$title, $desc])
                            <div class="flex gap-5">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-r from-brand-green to-brand-blue rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-6 h-6 text-surface-dark"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-white mb-1">{{ $title }}</h4>
                                    <p class="text-text-secondary">{{ $desc }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Contactos -->
                <section id="contactos"
                    class="py-20 bg-surface-card rounded-2xl shadow-xl animate-fade-up">
                    <div class="max-w-4xl mx-auto px-6">
                        <h2 class="text-4xl font-bold text-white mb-12 text-center">Entre em Contacto</h2>
                        <div class="grid md:grid-cols-3 gap-10 text-center">
                            <div>
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-brand-green to-brand-blue rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="map-pin" class="w-8 h-8 text-surface-dark"></i>
                                </div>
                                <h3 class="font-bold text-white mb-2">Morada</h3>
                                <p class="text-text-secondary">Rua da Inovação, 123<br>Porto, Portugal</p>
                            </div>

                            <div>
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-brand-green to-brand-blue rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="phone" class="w-8 h-8 text-surface-dark"></i>
                                </div>
                                <h3 class="font-bold text-white mb-2">Telefone</h3>
                                <p class="text-text-secondary">+351 123 456 789<br>Seg-Sex: 9h-18h</p>
                            </div>

                            <div>
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-brand-green to-brand-blue rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="mail" class="w-8 h-8 text-surface-dark"></i>
                                </div>
                                <h3 class="font-bold text-white mb-2">Email</h3>
                                <p class="text-text-secondary">info@pixelparts.pt</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>
@endsection
