// Master JS - Unificado
document.addEventListener('DOMContentLoaded', function() {

    // Initialize Lucide icons
    lucide.createIcons();

    // Header scroll effect
    const header = document.getElementById('header');
    function handleScroll() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', handleScroll);

    // Desktop hamburger menu (categorias)
    const menuToggle = document.getElementById('menu-toggle');
    const menuDropdown = document.getElementById('menu-dropdown');
    if (menuToggle && menuDropdown) {
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            menuDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!menuDropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                menuDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') menuDropdown.classList.add('hidden');
        });
    }

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Newsletter subscription
    const newsletterForm = document.getElementById('newsletter-form');
    const successMessage = document.getElementById('success-message');
    const emailInput = document.getElementById('email-input');
    const subscribeBtn = document.getElementById('subscribe-btn');

    if (subscribeBtn && newsletterForm && successMessage && emailInput) {
        subscribeBtn.addEventListener('click', function() {
            const email = emailInput.value.trim();
            if (!email) { alert('Por favor, introduza o seu email.'); return; }
            if (!isValidEmail(email)) { alert('Por favor, introduza um email válido.'); return; }

            newsletterForm.classList.add('hidden');
            successMessage.classList.remove('hidden');

            setTimeout(() => {
                newsletterForm.classList.remove('hidden');
                successMessage.classList.add('hidden');
                emailInput.value = '';
            }, 3000);
        });
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (mobileMenu) mobileMenu.classList.add('hidden');
            }
        });
    });

    // Product card hover
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-8px)');
        card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
    });

    // Add to cart functionality
    document.querySelectorAll('.product-add-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.product-card');
            if (!card) return;
            const title = card.querySelector('.product-title').textContent;
            const price = card.querySelector('.product-price').textContent;

            const originalText = this.innerHTML;
            this.innerHTML = '<i data-lucide="check"></i> Adicionado!';
            this.style.background = 'hsl(142, 76%, 36%)';
            lucide.createIcons();

            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.background = '';
                lucide.createIcons();
            }, 2000);

            console.log(`Produto adicionado: ${title} - ${price}`);
        });
    });

    // Intersection Observer for animations
    const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.style.animationPlayState = 'running';
        });
    }, observerOptions);

    document.querySelectorAll('.animate-fade-up, .animate-scale-in').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
});
