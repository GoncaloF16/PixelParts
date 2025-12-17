@extends('layouts.master')

@section('content')
<main class="bg-surface text-text-primary min-h-screen lg:pt-0 py-20">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-white mb-4">
                    Perguntas <span class="text-gradient-brand">Frequentes</span>
                </h1>
                <p class="text-text-secondary text-lg">
                    Encontre respostas rápidas às dúvidas mais comuns
                </p>
            </div>

            <!-- Search Box -->
            <div class="mb-10">
                <div class="relative max-w-2xl mx-auto">
                    <input type="text" id="faq-search"
                        placeholder="Procurar perguntas ou palavras-chave..."
                        class="w-full px-6 py-4 bg-surface-card text-text-primary rounded-xl border border-surface-dark focus:border-brand-green focus:outline-none focus:ring-2 focus:ring-brand-green/50 transition-all duration-300 pl-12">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-secondary"></i>
                </div>
            </div>

            <!-- No results message -->
            <div id="no-results" class="hidden text-center py-12 bg-surface-card rounded-xl">
                <i data-lucide="search-x" class="w-16 h-16 text-text-secondary mx-auto mb-4"></i>
                <p class="text-text-secondary text-lg">Nenhuma pergunta encontrada</p>
                <p class="text-text-secondary text-sm mt-2">Tente usar outras palavras-chave</p>
            </div>

            <!-- FAQ Sections -->
            <div id="faq-container" class="space-y-8">
                <!-- Encomendas e Entregas -->
                <div class="faq-section">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-brand-green/10 flex items-center justify-center">
                            <i data-lucide="truck" class="w-5 h-5 text-brand-green"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Encomendas e Entregas</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="entregas">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Quanto tempo demora a entrega?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    As entregas são realizadas em 24-48h úteis para Portugal Continental. Para as ilhas, o prazo pode variar entre 3-5 dias úteis. Receberá um código de tracking assim que a encomenda for expedida.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="entregas">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Qual o valor dos portes de envio?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Os portes de envio são gratuitos para encomendas superiores a 50€. Para valores inferiores, o custo é de 4,95€ para Portugal Continental e 9,95€ para as ilhas.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="entregas">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Posso alterar a morada de entrega após fazer a encomenda?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Sim, se a encomenda ainda não tiver sido expedida. Entre em contacto connosco através do email info@pixelparts.pt ou telefone +351 123 456 789 o mais rapidamente possível.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Produtos e Garantias -->
                <div class="faq-section">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-brand-green/10 flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-brand-green"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Produtos e Garantias</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="produtos">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Todos os produtos têm garantia?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Sim! Todos os nossos produtos são originais e vêm com garantia oficial do fabricante. A duração varia entre 2 a 5 anos dependendo do produto e fabricante. Além disso, tem direito à garantia legal de 2 anos.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="produtos">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Os produtos são novos ou recondicionados?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Todos os produtos vendidos na PixelParts são novos e selados. Não comercializamos produtos recondicionados. Caso algum produto seja apresentado como "Open Box" ou similar, será claramente indicado na descrição.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="produtos">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Posso verificar a compatibilidade dos componentes?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Claro! Na página de cada produto encontra informações de compatibilidade. Se tiver dúvidas, a nossa equipa técnica está disponível para ajudá-lo a escolher os componentes certos para o seu build.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagamentos e Devoluções -->
                <div class="faq-section">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-brand-green/10 flex items-center justify-center">
                            <i data-lucide="credit-card" class="w-5 h-5 text-brand-green"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Pagamentos e Devoluções</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="pagamentos">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Que métodos de pagamento aceitam?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Aceitamos pagamento por cartão de crédito/débito (Visa, Mastercard), MB WAY, transferência bancária e PayPal. Todos os pagamentos são processados de forma segura.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="pagamentos">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Posso devolver um produto?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Sim, tem 14 dias a partir da receção do produto para devolvê-lo, desde que esteja em perfeitas condições e na embalagem original. Entre em contacto connosco para iniciar o processo de devolução.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="pagamentos">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Quanto tempo demora o reembolso?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Após recebermos e validarmos o produto devolvido, o reembolso é processado em até 5 dias úteis. O tempo para o dinheiro aparecer na sua conta depende do seu banco, geralmente entre 3-5 dias úteis.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conta e Suporte -->
                <div class="faq-section">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-brand-green/10 flex items-center justify-center">
                            <i data-lucide="user-circle" class="w-5 h-5 text-brand-green"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Conta e Suporte</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="conta">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Preciso de criar conta para fazer uma encomenda?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Sim, é necessário criar uma conta para finalizar encomendas. Isto permite-lhe acompanhar os seus pedidos, gerir as suas informações e aceder ao histórico de compras. O registo é rápido e gratuito!
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="conta">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Como posso contactar o apoio ao cliente?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Pode contactar-nos por email (info@pixelparts.pt), telefone (+351 123 456 789) ou através da nossa página de contactos. O nosso horário de atendimento é de segunda a sexta, das 9h às 18h.
                                </p>
                            </div>
                        </div>

                        <div class="faq-item bg-surface-card rounded-xl border border-surface-dark hover:border-brand-green/30 transition-all duration-300"
                             data-category="conta">
                            <button class="faq-question w-full p-5 text-left flex items-center justify-between gap-4 group">
                                <h3 class="font-semibold text-white group-hover:text-brand-green transition-colors">
                                    Esqueci-me da minha palavra-passe. O que faço?
                                </h3>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform duration-300 chevron-icon flex-shrink-0"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5">
                                <p class="text-text-secondary leading-relaxed">
                                    Na página de login, clique em "Esqueceu-se da palavra-passe?" e siga as instruções. Receberá um email com um link para redefinir a sua palavra-passe de forma segura.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-16 text-center bg-surface-card rounded-xl p-10 border border-surface-dark">
                <div class="max-w-xl mx-auto">
                    <div class="w-16 h-16 bg-brand-green/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="message-circle" class="w-8 h-8 text-brand-green"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Ainda tem dúvidas?</h3>
                    <p class="text-text-secondary mb-6">
                        A nossa equipa está pronta para ajudá-lo
                    </p>
                    <a href="{{ route('about') }}#contactos"
                        class="inline-flex items-center gap-2 bg-brand-green text-surface-dark font-bold px-6 py-3 rounded-xl hover:bg-brand-green/90 transition-all duration-300">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                        Entre em Contacto
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('faq-search');
    const faqItems = document.querySelectorAll('.faq-item');
    const faqSections = document.querySelectorAll('.faq-section');
    const noResults = document.getElementById('no-results');
    const faqContainer = document.getElementById('faq-container');

    // Accordion functionality
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', function() {
            const item = this.closest('.faq-item');
            const answer = item.querySelector('.faq-answer');
            const icon = this.querySelector('.chevron-icon');
            const isOpen = !answer.classList.contains('hidden');

            // Close all other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    const otherIcon = otherItem.querySelector('.chevron-icon');
                    otherAnswer.classList.add('hidden');
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });

            // Toggle current item
            if (isOpen) {
                answer.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            } else {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCount = 0;

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
            const category = item.getAttribute('data-category');

            const matches = searchTerm === '' ||
                           question.includes(searchTerm) ||
                           answer.includes(searchTerm) ||
                           category.includes(searchTerm);

            if (matches) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
                const answerDiv = item.querySelector('.faq-answer');
                const icon = item.querySelector('.chevron-icon');
                answerDiv.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        });

        // Show/hide sections based on visible items
        faqSections.forEach(section => {
            const sectionItems = section.querySelectorAll('.faq-item');
            const hasVisibleItems = Array.from(sectionItems).some(item => item.style.display !== 'none');
            section.style.display = hasVisibleItems || searchTerm === '' ? 'block' : 'none';
        });

        // Show/hide no results
        if (visibleCount === 0 && searchTerm !== '') {
            noResults.style.display = 'block';
            faqContainer.style.display = 'none';
        } else {
            noResults.style.display = 'none';
            faqContainer.style.display = 'block';
        }
    });
});
</script>
@endsection
