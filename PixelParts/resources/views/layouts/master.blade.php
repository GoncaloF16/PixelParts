<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/css/index.css', 'resources/css/hero-particles.css', 'resources/js/app.js'])
  <title>PixelParts - Loja de Componentes</title>
</head>
<body class="bg-white text-gray-900 flex flex-col min-h-screen">

  <!-- Header -->
  <header id="mainHeader" class="fixed top-0 left-0 w-full z-50 bg-black transition-colors duration-500">
    <div class="container mx-auto flex items-center justify-between py-4 px-6">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/PixelParts.png') }}" alt="PixelParts Logo" class="h-16 w-auto logo-hover">
      </div>
      <nav class="hidden md:flex gap-8">
        <a href="#" class="text-white hover:text-brand-green transition">Início</a>
        <a href="#" class="text-white hover:text-brand-green transition">Produtos</a>
        <a href="#" class="text-white hover:text-brand-green transition">Sobre</a>
        <a href="#" class="text-white hover:text-brand-green transition">Contacto</a>
      </nav>
      <!-- Mobile Menu Button -->
      <button id="menuBtn" class="md:hidden text-white text-3xl">☰</button>
    </div>
    <!-- Mobile Dropdown -->
    <div id="mobileMenu" class="hidden flex-col gap-4 px-6 pb-4 bg-black/90 md:hidden">
      <a href="#" class="text-white hover:text-brand-green transition">Início</a>
      <a href="#" class="text-white hover:text-brand-green transition">Produtos</a>
      <a href="#" class="text-white hover:text-brand-green transition">Sobre</a>
      <a href="#" class="text-white hover:text-brand-green transition">Contacto</a>
    </div>
  </header>
  @yield('content')
   <!-- Footer -->
  <footer class="bg-black text-gray-300 py-12 mt-auto">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10">
      <!-- Logo + descrição -->
   <div class="mb-6">
    <h4 class="ps-14 text-white font-semibold mb-4 text-center md:text-left">Parceiros</h4>
    <div class="grid grid-cols-2 md:grid-cols-2 gap-6 justify-items-center items-center">
        <img src="{{ asset('images/PixelParts.png') }}" alt="PixelParts Logo" class="max-h-20 w-auto object-contain">
        <img src="https://cdn.freebiesupply.com/logos/large/2x/razer-logo-png-transparent.png" alt="Razer Logo" class="max-h-20 w-auto object-contain">
        <img src="https://www.nicepng.com/png/full/209-2090115_chicago-march-28-steelseries-logo-white.png" alt="SteelSeries Logo" class="max-h-20 w-auto object-contain">
        <img src="https://press.asus.com/assets/w_4035,h_3474/4a83deef-f73a-44e9-a9d6-1ae062de6fb9/ROG%20logo_red.png" alt="ROG Logo" class="max-h-20 w-auto object-contain">
    </div>
    </div>

      <!-- Links -->
      <div class="ps-10">
        <h4 class="text-white font-semibold mb-3">Links Rápidos</h4>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-brand-green">Início</a></li>
          <li><a href="#" class="hover:text-brand-green">Produtos</a></li>
          <li><a href="#" class="hover:text-brand-green">Sobre Nós</a></li>
          <li><a href="#" class="hover:text-brand-green">Contacto</a></li>
        </ul>
      </div>
      <!-- Social -->
      <div>
        <h4 class="text-white font-semibold mb-3">Segue-nos</h4>
        <div class="flex gap-4">
          <a href="#" class="hover:text-brand-green">Facebook</a>
          <a href="#" class="hover:text-brand-green">Instagram</a>
          <a href="#" class="hover:text-brand-green">Twitter</a>
        </div>
      </div>
    </div>
    <div class="text-center text-sm text-gray-500 mt-10">
      &copy; {{ date('Y') }} PixelParts. Todos os direitos reservados.
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    const header = document.getElementById('mainHeader');
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    // Menu Mobile
    menuBtn.addEvent

