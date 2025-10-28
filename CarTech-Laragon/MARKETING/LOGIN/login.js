// ==================================================
// SISTEMA DE LOGIN - CAR TECH
// ==================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando sistema de login...');
    inicializarLogin();
});

function inicializarLogin() {
    // Alternar entre abas
    const loginTabs = document.querySelectorAll('.login-tab');
    loginTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            alternarAba(this);
        });
    });

    // Validação em tempo real
    inicializarValidacao();

    // Prevenir autocomplete
    prevenirAutocomplete();

    // Auto-preenchimento para demonstração (remover em produção)
    preencherCredenciaisDemo();

    console.log('Sistema de login inicializado!');
}

// Alternar entre abas de login
function alternarAba(tabClicado) {
    const targetTab = tabClicado.getAttribute('data-tab');
    
    // Atualizar abas
    document.querySelectorAll('.login-tab').forEach(t => {
        t.classList.remove('active');
    });
    tabClicado.classList.add('active');
    
    // Atualizar formulários
    document.querySelectorAll('.login-form').forEach(form => {
        form.classList.remove('active');
    });
    
    // Corrigido: usar "Empresa" em vez de "Portaria"
    const formularioAlvo = document.getElementById(`form${targetTab.charAt(0).toUpperCase() + targetTab.slice(1)}`);
    if (formularioAlvo) {
        formularioAlvo.classList.add('active');
        
        // Foco no primeiro campo
        setTimeout(() => {
            const primeiroInput = formularioAlvo.querySelector('input[type="email"]');
            if (primeiroInput) {
                primeiroInput.focus();
            }
        }, 100);
    }
}

// Alternar visibilidade da senha
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (!input) {
        console.error('Input não encontrado:', inputId);
        return;
    }
    
    const toggle = input.parentNode.querySelector('.password-toggle');
    const icon = toggle.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        toggle.setAttribute('aria-label', 'Ocultar senha');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        toggle.setAttribute('aria-label', 'Mostrar senha');
    }
}

// Inicializar validação em tempo real
function inicializarValidacao() {
    const inputsObrigatorios = document.querySelectorAll('input[required]');
    
    inputsObrigatorios.forEach(input => {
        // Validação on blur
        input.addEventListener('blur', function() {
            validarCampo(this);
        });
        
        // Limpar erro on focus
        input.addEventListener('focus', function() {
            limparErroVisual(this);
        });
        
        // Validação em tempo real para email
        if (input.type === 'email') {
            input.addEventListener('input', function() {
                if (this.value) {
                    validarCampo(this);
                }
            });
        }
    });
}

// Validar campo individual
function validarCampo(campo) {
    const valor = campo.value.trim();
    
    if (!valor) {
        mostrarErroVisual(campo, 'Este campo é obrigatório');
        return false;
    }
    
    if (campo.type === 'email' && !validarEmail(valor)) {
        mostrarErroVisual(campo, 'Por favor, insira um e-mail válido');
        return false;
    }
    
    limparErroVisual(campo);
    return true;
}

// Validar formato de e-mail
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Mostrar erro visual no campo
function mostrarErroVisual(campo, mensagem) {
    campo.style.borderColor = '#ef4444';
    campo.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
    
    // Criar ou atualizar mensagem de erro
    let erroElement = campo.parentNode.querySelector('.error-message-campo');
    if (!erroElement) {
        erroElement = document.createElement('div');
        erroElement.className = 'error-message-campo';
        campo.parentNode.appendChild(erroElement);
    }
    
    erroElement.textContent = mensagem;
    erroElement.style.cssText = `
        color: #ef4444;
        font-size: 11px;
        margin-top: 4px;
        display: block;
    `;
}

// Limpar erro visual do campo
function limparErroVisual(campo) {
    campo.style.borderColor = '#404040';
    campo.style.boxShadow = 'none';
    
    const erroElement = campo.parentNode.querySelector('.error-message-campo');
    if (erroElement) {
        erroElement.style.display = 'none';
    }
}

// Prevenir autocomplete
function prevenirAutocomplete() {
    document.querySelectorAll('input').forEach(input => {
        input.setAttribute('autocomplete', 'off');
        input.setAttribute('autocapitalize', 'off');
        input.setAttribute('autocorrect', 'off');
        input.setAttribute('spellcheck', 'false');
    });
}

// Preencher credenciais para demonstração (REMOVER EM PRODUÇÃO)
function preencherCredenciaisDemo() {
    const emailAdm = document.getElementById('emailAdm');
    const senhaAdm = document.getElementById('senhaAdm');
    
    if (emailAdm && senhaAdm) {
        // Apenas preencher se estiver vazio
        if (!emailAdm.value) emailAdm.value = 'cartech.mecanicas@gmail.com';
        if (!senhaAdm.value) senhaAdm.value = 'uni9TI2025';
        
        console.log('Credenciais de demonstração disponíveis');
    }
}

// Validar formulário antes do envio
function validarFormulario(formId) {
    const formulario = document.getElementById(formId);
    const inputs = formulario.querySelectorAll('input[required]');
    let valido = true;
    
    inputs.forEach(input => {
        if (!validarCampo(input)) {
            valido = false;
            if (valido) {
                input.focus();
            }
        }
    });
    
    return valido;
}

// Adicionar event listeners para os formulários
document.addEventListener('DOMContentLoaded', function() {
    // Validar formulário Empresa antes do envio
    const formEmpresa = document.getElementById('formEmpresa');
    if (formEmpresa) {
        formEmpresa.addEventListener('submit', function(e) {
            if (!validarFormulario('formEmpresa')) {
                e.preventDefault();
            }
        });
    }
    
    // Validar formulário ADM antes do envio
    const formAdm = document.getElementById('formAdm');
    if (formAdm) {
        formAdm.addEventListener('submit', function(e) {
            if (!validarFormulario('formAdm')) {
                e.preventDefault();
            }
        });
    }
});

// Adicionar CSS para animações se não existir
if (!document.querySelector('#login-animations')) {
    const estiloAnimacoes = document.createElement('style');
    estiloAnimacoes.id = 'login-animations';
    estiloAnimacoes.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-form {
            display: none;
        }
        
        .login-form.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .error-message-campo {
            display: none;
        }
    `;
    document.head.appendChild(estiloAnimacoes);
}

// Debug: Verificar se todas as funções estão carregando
console.log('Funções JavaScript carregadas:', {
    togglePassword: typeof togglePassword,
    alternarAba: typeof alternarAba,
    validarEmail: typeof validarEmail,
    inicializarLogin: typeof inicializarLogin
});