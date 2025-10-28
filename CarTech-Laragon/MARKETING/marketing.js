// marketing.js

class MarketingCarTech {
    constructor() {
        this.init();
    }

    init() {
        console.log('🚗 CarTech Marketing inicializado!');
        this.setupEventListeners();
        this.setupAnimations();
        this.setupScrollEffects();
        this.animateStats();
        this.setupFormValidation();
    }

    setupEventListeners() {
        // Menu mobile
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenuButton.classList.toggle('text-white');
            });

            // Fechar menu ao clicar em links
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.classList.remove('text-white');
                });
            });
        }

        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Admin access
        const adminLink = document.getElementById('admin-access');
        if (adminLink) {
            adminLink.addEventListener('click', (e) => {
                e.preventDefault();
                this.showAdminModal();
            });
        }
    }

    setupAnimations() {
        // Intersection Observer para animações on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    
                    // Animações específicas por tipo
                    if (entry.target.classList.contains('feature-card')) {
                        this.animateCard(entry.target);
                    }
                    if (entry.target.classList.contains('stat-card')) {
                        this.animateStat(entry.target);
                    }
                }
            });
        }, observerOptions);

        // Observar elementos com animação
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Efeito de digitação no hero
        this.typewriterEffect();
    }

    typewriterEffect() {
        const elements = document.querySelectorAll('.typewriter');
        elements.forEach((element, index) => {
            const text = element.textContent;
            element.textContent = '';
            element.style.width = '0';
            
            setTimeout(() => {
                let i = 0;
                const typing = setInterval(() => {
                    if (i < text.length) {
                        element.textContent += text.charAt(i);
                        i++;
                    } else {
                        clearInterval(typing);
                    }
                }, 100);
            }, index * 500);
        });
    }

    setupScrollEffects() {
        let lastScrollY = window.scrollY;
        const header = document.querySelector('header');

        window.addEventListener('scroll', () => {
            // Header effect
            if (window.scrollY > 100) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }

            // Parallax effect
            this.parallaxEffect();

            // Atualizar navegação ativa
            this.updateActiveNav();

            lastScrollY = window.scrollY;
        });
    }

    parallaxEffect() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.parallax');
        
        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    }

    updateActiveNav() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollY >= (sectionTop - 100)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    }

    animateStats() {
        const counters = document.querySelectorAll('.stats-counter');
        const speed = 200;

        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(() => this.animateStats(), 1);
            } else {
                counter.innerText = target;
            }
        });
    }

    animateCard(card) {
        card.style.animation = 'fadeIn 0.6s ease-out';
    }

    animateStat(stat) {
        stat.style.animation = 'slideInUp 0.6s ease-out';
    }

    setupFormValidation() {
        const form = document.getElementById('contact-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if (this.validateForm(form)) {
                    this.submitForm(form);
                }
            });

            // Validação em tempo real
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
                
                input.addEventListener('input', () => {
                    this.clearFieldError(input);
                });
            });
        }
    }

    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Limpar erro anterior
        this.clearFieldError(field);

        // Validações específicas
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Por favor, insira um e-mail válido.';
            }
        }

        if (field.type === 'tel' && value) {
            const phoneRegex = /^[\d\s\-\(\)]+$/;
            if (!phoneRegex.test(value) || value.replace(/\D/g, '').length < 10) {
                isValid = false;
                errorMessage = 'Por favor, insira um telefone válido.';
            }
        }

        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'Este campo é obrigatório.';
        }

        if (!isValid) {
            this.showFieldError(field, errorMessage);
        }

        return isValid;
    }

    showFieldError(field, message) {
        field.classList.add('border-red-500');
        
        let errorElement = field.parentNode.querySelector('.field-error');
        if (!errorElement) {
            errorElement = document.createElement('p');
            errorElement.className = 'field-error text-red-400 text-sm mt-1';
            field.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }

    clearFieldError(field) {
        field.classList.remove('border-red-500');
        
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    async submitForm(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Mostrar loading
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
        submitBtn.disabled = true;

        try {
            // Simular envio (em produção, substituir por fetch real)
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            this.showNotification('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
            form.reset();
            
        } catch (error) {
            this.showNotification('Erro ao enviar mensagem. Tente novamente.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-transform duration-300 ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 'bg-blue-600'
        } text-white`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation' : 'info'}-circle mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.classList.add('translate-x-0');
        }, 100);
        
        // Remover após 5 segundos
        setTimeout(() => {
            notification.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }

    showAdminModal() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Acesso Administrativo</h3>
                    <button class="text-gray-400 hover:text-white" onclick="this.closest('.fixed').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-gray-300 mb-4">Esta área é restrita para administradores do sistema.</p>
                <div class="flex justify-end space-x-3">
                    <button class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-500 transition duration-300" 
                            onclick="this.closest('.fixed').remove()">
                        Cancelar
                    </button>
                    <a href="LOGIN/login.php?tipo=adm" 
                       class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-500 transition duration-300 text-white">
                        Acessar como Admin
                    </a>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Fechar modal ao clicar fora
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    new MarketingCarTech();
});

// Utilitários globais
window.debounce = function(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

window.throttle = function(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
};