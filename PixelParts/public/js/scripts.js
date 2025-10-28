document.addEventListener("DOMContentLoaded", function () {
    // Inicializa Lucide icons
    lucide.createIcons({ width: 20, height: 20 }); // 👈 ícones ligeiramente mais pequenos

    // Header scroll effect
    const header = document.getElementById("header");
    function handleScroll() {
        if (window.scrollY > 50) header.classList.add("scrolled");
        else header.classList.remove("scrolled");
    }
    window.addEventListener("scroll", handleScroll);

    // Desktop hamburger menu (categorias)
    const menuToggle = document.getElementById("menu-toggle");
    const menuDropdown = document.getElementById("menu-dropdown");

    if (menuToggle && menuDropdown) {
        menuToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            menuDropdown.classList.toggle("hidden");
        });

        document.addEventListener("click", (e) => {
            if (!menuDropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                menuDropdown.classList.add("hidden");
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") menuDropdown.classList.add("hidden");
        });
    }

    // Desktop user dropdown (login)
    const userMenuButton = document.getElementById("userMenuButton");
    const userDropdown = document.getElementById("userDropdown");

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener("click", (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle("hidden");
        });

        document.addEventListener("click", (e) => {
            if (!userDropdown.contains(e.target) && !userMenuButton.contains(e.target)) {
                userDropdown.classList.add("hidden");
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") userDropdown.classList.add("hidden");
        });
    }

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById("mobile-menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    }

    // Newsletter subscription
    const newsletterForm = document.getElementById("newsletter-form");
    const successMessage = document.getElementById("success-message");
    const emailInput = document.getElementById("email-input");
    const subscribeBtn = document.getElementById("subscribe-btn");

    if (subscribeBtn && newsletterForm && successMessage && emailInput) {
        subscribeBtn.addEventListener("click", () => {
            const email = emailInput.value.trim();
            if (!email) {
                alert("Por favor, introduza o seu email.");
                return;
            }
            if (!isValidEmail(email)) {
                alert("Por favor, introduza um email válido.");
                return;
            }

            newsletterForm.classList.add("hidden");
            successMessage.classList.remove("hidden");

            setTimeout(() => {
                newsletterForm.classList.remove("hidden");
                successMessage.classList.add("hidden");
                emailInput.value = "";
            }, 3000);
        });
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({ behavior: "smooth", block: "start" });
                if (mobileMenu) mobileMenu.classList.add("hidden");
            }
        });
    });

    // Product card hover effect
    document.querySelectorAll(".product-card").forEach((card) => {
        card.addEventListener("mouseenter", () => (card.style.transform = "translateY(-8px)"));
        card.addEventListener("mouseleave", () => (card.style.transform = "translateY(0)"));
    });

    // Atualiza o contador do carrinho (função global)
    window.updateCartCount = function(newCount) {
        const cartCountEl = document.getElementById("cart-count");
        if (!cartCountEl) return;

        if (newCount > 0) {
            cartCountEl.textContent = newCount;
            cartCountEl.classList.remove("hidden");
        } else {
            cartCountEl.classList.add("hidden");
        }
    }

    // Cria um toast (função global)
    window.showToast = function(message, isError = false) {
        const existingToast = document.getElementById("toast-message");
        if (existingToast) existingToast.remove();

        const toast = document.createElement("div");
        toast.id = "toast-message";
        toast.className = `
            fixed bottom-5 left-5 px-4 py-3 rounded-lg shadow-lg flex items-center gap-3
            z-[9999] animate-fade-in text-white text-sm
            ${isError ? "bg-red-600" : "bg-gray-800"}
        `;

        const icon = document.createElement("i");
        icon.setAttribute("data-lucide", isError ? "alert-triangle" : "check-circle");
        icon.className = isError ? "w-5 h-5 text-yellow-300" : "w-5 h-5 text-green-400";
        toast.appendChild(icon);

        const text = document.createElement("span");
        text.textContent = message;
        toast.appendChild(text);

        const closeBtn = document.createElement("button");
        closeBtn.innerHTML = "&times;";
        closeBtn.className = "ml-3 text-gray-400 hover:text-white text-lg";
        closeBtn.onclick = () => toast.remove();
        toast.appendChild(closeBtn);

        document.body.appendChild(toast);
        lucide.createIcons();

        // Sai automaticamente após 4 segundos
        setTimeout(() => {
            toast.classList.add("opacity-0", "transition-opacity", "duration-500");
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }

    // Animações de entrada
    const observerOptions = { threshold: 0.1, rootMargin: "0px 0px -50px 0px" };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) entry.target.style.animationPlayState = "running";
        });
    }, observerOptions);

    document.querySelectorAll(".animate-fade-up, .animate-scale-in").forEach((el) => {
        el.style.animationPlayState = "paused";
        observer.observe(el);
    });

    // Estilo para animação do toast
    const style = document.createElement("style");
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease forwards;
        }
    `;
    document.head.appendChild(style);
});
