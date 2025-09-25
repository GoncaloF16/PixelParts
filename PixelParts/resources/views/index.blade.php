@extends('.layouts/master')
  <!-- Hero / Particles Floating Background -->
  <section class="relative h-screen flex items-center justify-center text-white hero-particles">
    <div class="absolute inset-0 particles-container bg-black"> <!-- Fundo preto para contraste com partículas -->
      <div class="particle particle-1"></div>
      <div class="particle particle-2"></div>
      <div class="particle particle-3"></div>
      <div class="particle particle-4"></div>
      <div class="particle particle-5"></div>
      <div class="particle particle-6"></div>
      <div class="particle particle-7"></div>
      <div class="particle particle-8"></div>
      <div class="particle particle-9"></div>
      <div class="particle particle-10"></div>
      <div class="particle particle-11"></div>
      <div class="particle particle-12"></div>
      <div class="particle particle-13"></div>
      <div class="particle particle-14"></div>
      <div class="particle particle-15"></div>
      <div class="particle particle-16"></div>
      <div class="particle particle-17"></div>
      <div class="particle particle-18"></div>
      <div class="particle particle-19"></div>
      <div class="particle particle-20"></div>
      <div class="particle particle-21"></div>
      <div class="particle particle-22"></div>
      <div class="particle particle-23"></div>
      <div class="particle particle-24"></div>
      <div class="particle particle-25"></div>
      <div class="particle particle-26"></div>
      <div class="particle particle-27"></div>
      <div class="particle particle-28"></div>
      <div class="particle particle-29"></div>
      <div class="particle particle-30"></div>
      <div class="particle particle-31"></div>
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>

    <div class="relative z-10 text-center px-6">
      <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">Componentes de Alta Performance</h1>
      <p class="text-lg md:text-2xl text-gray-200 mb-6">Constrói o setup dos teus sonhos com a PixelParts</p>
      <a href="#produtos" class="bg-gradient-to-r from-brand-green to-brand-darkblue text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">Explorar Produtos</a>
    </div>
  </section>

  <!-- Produtos -->
  <main id="produtos" class="flex-1 py-20 bg-gray-50">
    <div class="container mx-auto px-6">
      <h2 class="text-4xl font-bold text-center text-brand-darkblue mb-12">Produtos em Destaque</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">

        <!-- Produto -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-2">
          <img src="https://m.media-amazon.com/images/I/81k2Gmal+VL._AC_SL1500_.jpg" alt="Placa Gráfica" class="h-48 w-full object-contain p-6 bg-gray-100">
          <div class="p-6 text-center">
            <h3 class="text-xl font-semibold text-brand-darkblue">NVIDIA RTX 4070</h3>
            <p class="text-gray-500">8GB GDDR6X</p>
            <span class="block text-2xl font-bold text-brand-green my-4">€699,99</span>
            <button class="bg-gradient-to-r from-brand-green to-brand-darkblue text-white px-5 py-2 rounded-lg font-semibold hover:opacity-90 transition">Adicionar ao Carrinho</button>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- Newsletter -->
  <section class="bg-gradient-to-r from-brand-green to-brand-lightblue py-16">
    <div class="container mx-auto px-6 text-center">
      <h3 class="text-3xl font-bold text-white mb-4">Subscreve à nossa Newsletter</h3>
      <p class="text-white/80 mb-6">Recebe novidades, promoções e dicas exclusivas no teu email.</p>
      <form class="flex flex-col md:flex-row justify-center gap-4 max-w-xl mx-auto">
        <input type="email" placeholder="O teu email" class="rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-brand-darkblue" required>
        <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-brand-darkblue transition">Subscrever</button>
      </form>
    </div>
  </section>

