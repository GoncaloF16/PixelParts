 <!DOCTYPE html>
 <html lang="pt">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>PixelParts - Componentes Gaming de Elite</title>
     <meta name="description"
         content="Descobre componentes gaming de alta performance. Placas gráficas, processadores, memórias e mais para elevar o teu setup gaming.">
     <!-- Tailwind CSS -->
     <script src="https://cdn.tailwindcss.com"></script>

     <!-- Custom Styles -->
     <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

     <!-- Lucide Icons -->
     <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

 </head>

 <body class="min-h-screen bg-background pt-[150px] md:pt-0">
        <!-- Floating Cart Button -->
    <a href="{{ route('cart.index') }}"
        class="fixed bottom-6 right-6 w-16 h-16 bg-gradient-to-r from-brand-green to-brand-blue rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-300 z-50">
        <i data-lucide="shopping-cart" class="w-7 h-7 text-white"></i>
        <span id="cart-count"
            class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
            {{ count(session('cart', [])) }}
        </span>
    </a>


     <!-- Toast template (invisível, usado pelo JS) -->
    <div id="toast-template" class="hidden">
        <div id="toast"
            class="fixed bottom-6 left-6 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 opacity-0 translate-y-4 transition-all duration-300 ease-in-out z-50"
            style="animation: fadeInUp 0.3s ease-out forwards;">
            <div class="flex items-center justify-center w-6 h-6 bg-white rounded-full">
                <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                <span class="text-green-600 font-bold" style="display: none;">✓</span>
            </div>
            <span class="flex-1 toast-message"></span>
            <button class="text-white hover:text-gray-200 ml-2" onclick="closeToast(this)">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span class="text-white font-bold" style="display: none;">&times;</span>
            </button>
        </div>
    </div>
     <!-- Header -->
     <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
         <div class="container mx-auto px-6 py-4">
             <div class="flex items-center justify-between">
                 <!-- Logo -->
                 <div class="flex items-center space-x-3 logo-hover">
                     <a href="{{ route('home') }}"> <img src="{{ asset('images/PixelParts.png') }}"
                             alt="PixelParts Logo" class="h-[80px] w-[100px]"> </a>
                 </div>

                 <!-- Desktop Navigation -->
                 <nav class="hidden md:flex items-center space-x-8">
                     <a href="#inicio"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Início</a>
                     <a href="#produtos"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Produtos</a>
                     <a href="#servicos"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Serviços</a>
                     <a href="#sobre"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Sobre</a>
                     <a href="#contacto"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Contacto</a>

                     @guest
                         <a href="{{ route('login') }}"
                             class="bg-gradient-to-r from-brand-green to-brand-blue text-surface-dark px-4 py-2 rounded-md font-semibold text-sm hover:scale-105 transition-transform duration-300 glow-brand">
                             Entrar
                         </a>
                     @endguest

                     @auth
                         <form method="POST" action="{{ route('logout') }}" class="flex items-center m-0 p-0">
                             @csrf
                             <button type="submit"
                                 class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded-md font-semibold text-sm hover:bg-red-700 transition-colors duration-300">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                     <path stroke-linecap="round" stroke-linejoin="round"
                                         d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m0 6v1" />
                                 </svg>
                                 Logout
                             </button>
                         </form>
                     @endauth
                 </nav>



                 <!-- Mobile Menu Button -->
                 <button id="mobile-menu-btn" class="md:hidden text-text-primary">
                     <i data-lucide="menu" class="w-6 h-6"></i>
                 </button>
             </div>

             <!-- Mobile Navigation -->
             <nav id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-border">
                 <div class="flex flex-col space-y-4 pt-4">
                     <a href="#inicio"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Início</a>
                     <a href="#produtos"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Produtos</a>
                     <a href="#servicos"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Serviços</a>
                     <a href="#sobre"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Sobre</a>
                     <a href="#contacto"
                         class="text-text-primary hover:text-brand-green transition-colors duration-300 font-medium">Contacto</a>
                     <a href="{{ route('login') }}"
                         class="bg-gradient-to-r from-brand-green to-brand-blue text-surface-dark px-4 py-2 rounded-md font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">Entrar</a>
                 </div>
             </nav>
         </div>
     </header>
     @yield('content')
     <!-- Footer -->
     <footer class="bg-surface-card border-t border-border py-16">
         <div class="container mx-auto px-6">
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                 <!-- Company Info -->
                 <div class="space-y-4">
                     <div class="flex items-center space-x-3 logo-hover">
                         <img src="{{ asset('images/PixelParts.png') }}" alt="PixelParts Logo"
                             class="h-[40px] w-[50px]">
                         <span class="text-2xl font-bold text-gradient-brand">PixelParts</span>
                     </div>
                     <p class="text-text-secondary">
                         A tua loja de confiança para componentes gaming de elite.
                         Performance, qualidade e inovação em cada produto.
                     </p>
                     <div class="flex space-x-4">
                         <a href="#"
                             class="w-10 h-10 bg-brand-green/20 rounded-lg flex items-center justify-center text-brand-green hover:bg-brand-green hover:text-surface-dark transition-all duration-300">
                             <i data-lucide="facebook" class="w-5 h-5"></i>
                         </a>
                         <a href="#"
                             class="w-10 h-10 bg-brand-green/20 rounded-lg flex items-center justify-center text-brand-green hover:bg-brand-green hover:text-surface-dark transition-all duration-300">
                             <i data-lucide="twitter" class="w-5 h-5"></i>
                         </a>
                         <a href="#"
                             class="w-10 h-10 bg-brand-green/20 rounded-lg flex items-center justify-center text-brand-green hover:bg-brand-green hover:text-surface-dark transition-all duration-300">
                             <i data-lucide="instagram" class="w-5 h-5"></i>
                         </a>
                     </div>
                 </div>

                 <!-- Quick Links -->
                 <div>
                     <h4 class="text-lg font-bold text-text-primary mb-4">Links Rápidos</h4>
                     <ul class="space-y-3">
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Início</a>
                         </li>
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Produtos</a>
                         </li>
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Serviços</a>
                         </li>
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Sobre
                                 Nós</a></li>
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Contacto</a>
                         </li>
                     </ul>
                 </div>

                 <!-- Categories -->
                 <div>
                     <h4 class="text-lg font-bold text-text-primary mb-4">Categorias</h4>
                     <ul class="space-y-3">
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Placas
                                 Gráficas</a></li>
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Processadores</a>
                         </li>
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Memórias</a>
                         </li>
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Armazenamento</a>
                         </li>
                         <li><a href="#"
                                 class="text-text-secondary hover:text-brand-green transition-colors duration-300">Motherboards</a>
                         </li>
                     </ul>
                 </div>

                 <!-- Contact -->
                 <div>
                     <h4 class="text-lg font-bold text-text-primary mb-4">Contacto</h4>
                     <ul class="space-y-3">
                         <li class="flex items-center space-x-3 text-text-secondary">
                             <i data-lucide="map-pin" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
                             <span>Rua da Inovação, 123<br>Porto, Portugal</span>
                         </li>
                         <li class="flex items-center space-x-3 text-text-secondary">
                             <i data-lucide="phone" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
                             <span>+351 123 456 789</span>
                         </li>
                         <li class="flex items-center space-x-3 text-text-secondary">
                             <i data-lucide="mail" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
                             <span>info@pixelparts.pt</span>
                         </li>
                     </ul>
                 </div>
             </div>

             <!-- Bottom Bar -->
             <div
                 class="border-t border-gray-700 border-border pt-8 flex flex-col md:flex-row justify-between items-center">
                 <p class="text-text-secondary text-sm mb-4 md:mb-0">
                     © 2024 PixelParts. Todos os direitos reservados.
                 </p>
                 <div class="flex space-x-6 text-sm">
                     <a href="#"
                         class="text-text-secondary hover:text-brand-green transition-colors duration-300">Política de
                         Privacidade</a>
                     <a href="#"
                         class="text-text-secondary hover:text-brand-green transition-colors duration-300">Termos de
                         Serviço</a>
                     <a href="#"
                         class="text-text-secondary hover:text-brand-green transition-colors duration-300">Cookies</a>
                 </div>
             </div>
         </div>
     </footer>

     <script src="{{ asset('js/scripts.js') }}"></script>
     <script src="{{ asset('js/products.js') }}"></script>
     <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/lucide.min.js"></script>
     <script>
         lucide.replace();
     </script>


     @if (session('success'))
         <div id="toast"
             class="fixed bottom-6 left-6 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 opacity-0 translate-y-4 transition-all duration-300 ease-in-out z-50"
             style="animation: fadeInUp 0.3s ease-out forwards;">
             <!-- Ícone check (fallback para texto se Lucide falhar) -->
             <div class="flex items-center justify-center w-6 h-6 bg-white rounded-full">
                 <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                 <!-- Fallback: Texto '✓' se ícone não carregar -->
                 <span class="text-green-600 font-bold" style="display: none;">✓</span>
             </div>

             <!-- Mensagem -->
             <span class="flex-1">{{ session('success') }}</span>

             <!-- Botão fechar -->
             <button onclick="closeToast()" class="text-white hover:text-gray-200 ml-2">
                 <i data-lucide="x" class="w-5 h-5"></i>
                 <!-- Fallback: &times; -->
                 <span class="text-white font-bold" style="display: none;">&times;</span>
             </button>
         </div>

         <style>
             @keyframes fadeInUp {
                 to {
                     opacity: 1;
                     transform: translateY(0);
                 }
             }
         </style>

         <script>
             function closeToast() {
                 const toast = document.getElementById('toast');
                 if (toast) {
                     toast.style.opacity = '0';
                     toast.style.transform = 'translateY(16px)'; // bottom-4 equivalent
                     setTimeout(() => toast.remove(), 300);
                 }
             }

             document.addEventListener('DOMContentLoaded', () => {
                 // Inicializar ícones Lucide (com fallback)
                 if (typeof lucide !== 'undefined') {
                     lucide.createIcons(); // Use createIcons para elementos existentes
                 } else {
                     // Fallback: Mostrar texto em vez de ícones
                     document.querySelector('[data-lucide="check"]').style.display = 'none';
                     document.querySelector('[data-lucide="check"] + span').style.display = 'block';
                     document.querySelector('[data-lucide="x"]').style.display = 'none';
                     document.querySelector('[data-lucide="x"] + span').style.display = 'block';
                 }

                 // Auto fechar após 3 segundos
                 setTimeout(() => {
                     closeToast();
                 }, 3000);
             });
         </script>
     @endif

 </body>

 </html>
