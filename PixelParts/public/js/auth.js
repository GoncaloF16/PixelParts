document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();

    // Password visibility toggle
    initPasswordToggle();

    // Password strength checker (for register page)
    initPasswordStrength();

    // Form validation
    initFormValidation();

    // Enhanced animations
    initAnimations();
});

/**
 * Password Visibility Toggle
 */
function initPasswordToggle() {
    const toggleButtons = document.querySelectorAll('.password-toggle');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                targetInput.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }

            // Refresh lucide icons
            lucide.createIcons();
        });
    });
}

/**
 * Password Strength Checker
 */
function initPasswordStrength() {
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');

    if (!passwordInput || !strengthFill || !strengthText) return;

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);

        // Update strength bar
        strengthFill.style.width = strength.percentage + '%';
        strengthText.textContent = strength.text;

        // Update color based on strength
        const colors = {
            'Muito fraca': '#ef4444',
            'Fraca': '#f97316',
            'Média': '#eab308',
            'Forte': '#22c55e',
            'Muito forte': '#059669'
        };

        strengthFill.style.background = colors[strength.text] || 'var(--gradient-brand)';
    });
}

/**
 * Calculate password strength
 */
function calculatePasswordStrength(password) {
    let score = 0;

    if (password.length >= 8) score += 20;
    if (password.length >= 12) score += 10;
    if (/[a-z]/.test(password)) score += 15;
    if (/[A-Z]/.test(password)) score += 15;
    if (/[0-9]/.test(password)) score += 15;
    if (/[^A-Za-z0-9]/.test(password)) score += 25;

    let text = 'Muito fraca';
    if (score >= 20) text = 'Fraca';
    if (score >= 40) text = 'Média';
    if (score >= 70) text = 'Forte';
    if (score >= 90) text = 'Muito forte';

    return {
        percentage: Math.min(score, 100),
        text: text
    };
}

/**
 * Form Validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('#login-form, #register-form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });

        // === Real-time Validation ===
        // Only on blur and if already had error
        const inputs = form.querySelectorAll('.form-input, input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            // Validate only on blur
            input.addEventListener('blur', function() {
                // Only validate if field has value or is required
                if (this.value.trim().length > 0 || this.hasAttribute('required')) {
                    validateInput(this);
                }
            });

            // During typing, only remove error if already marked as error
            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    // Re-validate to remove error if corrected
                    validateInput(this);
                }
            });
        });
    });
}

/**
 * Validate individual input
 */
function validateInput(input) {
    const value = input.value.trim();
    const type = input.type;
    const name = input.name;
    let isValid = true;
    let errorMessage = '';

    // Remove existing error
    input.classList.remove('error');
    const existingError = input.parentNode.querySelector('.form-error');
    if (existingError) {
        existingError.remove();
    }

    // If field is empty and not required, don't validate
    if (!value && !input.hasAttribute('required')) {
        return true;
    }

    // Required validation
    if (input.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'Este campo é obrigatório.';
    }

    // Email validation
    if (type === 'email' && value && value.length > 0) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = 'Por favor, introduza um email válido.';
        }
    }

    // Password validation - apenas se tiver valor e estiver no registo
    if (type === 'password' && name === 'password' && value && value.length > 0) {
        const registerForm = document.getElementById('register-form');
        // Only validate size on register form
        if (registerForm && value.length < 8) {
            isValid = false;
            errorMessage = 'A password deve ter pelo menos 8 caracteres.';
        }
    }

    // Password confirmation - apenas se tiver valor
    if (name === 'password_confirmation' && value && value.length > 0) {
        const passwordInput = document.getElementById('password');
        if (passwordInput && value !== passwordInput.value) {
            isValid = false;
            errorMessage = 'As passwords não coincidem.';
        }
    }

    // Show error if validation failed
    if (!isValid) {
        input.classList.add('error');
        const errorSpan = document.createElement('span');
        errorSpan.className = 'form-error';
        errorSpan.textContent = errorMessage;

        // Insert after input or its parent container
        const container = input.closest('.relative') || input;
        container.parentNode.insertBefore(errorSpan, container.nextSibling);
    }

    return isValid;
}

/**
 * Validate entire form
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('.form-input[required], .form-input[type="email"]');
    let isValid = true;

    inputs.forEach(input => {
        if (!validateInput(input)) {
            isValid = false;
        }
    });

    // Check terms acceptance for register form
    const termsCheckbox = form.querySelector('input[name="terms"]');
    if (termsCheckbox && !termsCheckbox.checked) {
        isValid = false;
        showToast('Por favor, aceite os termos de serviço.', 'error');
    }

    return isValid;
}

/**
 * Enhanced Animations
 */
function initAnimations() {
    // Form card entrance animation
    const authCard = document.querySelector('.auth-card');
    if (authCard) {
        authCard.style.opacity = '0';
        authCard.style.transform = 'translateY(20px)';

        setTimeout(() => {
            authCard.style.transition = 'all 0.6s ease';
            authCard.style.opacity = '1';
            authCard.style.transform = 'translateY(0)';
        }, 100);
    }

    // Input focus animations
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
        });

        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Button hover enhancements
    const buttons = document.querySelectorAll('.auth-btn-primary, .auth-btn-social');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });

        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(0) scale(0.98)';
        });

        button.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-2px) scale(1)';
        });
    });
}

/**
 * Toast notification system
 */
function showToast(message, type = 'info') {
    // Remove existing toast
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i data-lucide="${getToastIcon(type)}" class="w-5 h-5"></i>
            <span>${message}</span>
        </div>
    `;

    // Style toast
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: hsl(var(--surface-card));
        border: 1px solid hsl(var(--border));
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        color: hsl(var(--text-primary));
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;

    if (type === 'error') {
        toast.style.borderColor = 'hsl(var(--error))';
    } else if (type === 'success') {
        toast.style.borderColor = 'hsl(var(--success))';
    }

    // Add to page
    document.body.appendChild(toast);
    lucide.createIcons();

    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);

    // Auto remove
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 4000);
}

/**
 * Get icon for toast type
 */
function getToastIcon(type) {
    const icons = {
        'info': 'info',
        'success': 'check-circle',
        'error': 'alert-circle',
        'warning': 'alert-triangle'
    };

    return icons[type] || 'info';
}

/**
 * CSRF Token Setup for AJAX requests
 */
function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
    }
}

// Initialize CSRF
setupCSRF();
