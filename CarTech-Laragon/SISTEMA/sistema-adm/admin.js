// ==================================================
// ADMIN DASHBOARD - CAR TECH
// ==================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando painel administrativo...');
    inicializarAdmin();
});

function inicializarAdmin() {
   // Navegação entre abas
function inicializarNavegacao() {
    const navItems = document.querySelectorAll('.nav-item[data-tab]');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Não prevenir default - deixar o link natural funcionar
            // O PHP já cuida de mostrar a aba correta
            
            // Apenas atualizar visualmente se necessário
            navItems.forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });
}
    
    // Máscaras para formulários
    inicializarMascaras();
    
    // Modal de detalhes
    inicializarModal();
    
    // Busca automática de CEP
    inicializarBuscaCEP();
    
    // WhatsApp
    inicializarWhatsApp();
    
    // Busca em tempo real
    inicializarBusca();
    
    console.log('Painel administrativo inicializado!');
}

// Navegação entre abas
function inicializarNavegacao() {
    const navItems = document.querySelectorAll('.nav-item[data-tab]');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remover active de todos
            navItems.forEach(nav => nav.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            
            // Adicionar active no selecionado
            this.classList.add('active');
            const targetTab = this.getAttribute('data-tab');
            const targetElement = document.getElementById(targetTab);
            if (targetElement) {
                targetElement.classList.add('active');
            }
        });
    });
}

// Máscaras para formulários
function inicializarMascaras() {
    // Máscara para CPF/CNPJ
    const documentoInput = document.getElementById('documento');
    const tipoDocumento = document.getElementById('tipo_documento');
    
    if (tipoDocumento && documentoInput) {
        tipoDocumento.addEventListener('change', function() {
            aplicarMascaraDocumento();
        });
        
        documentoInput.addEventListener('input', function() {
            aplicarMascaraDocumento();
        });
    }
    
    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            
            e.target.value = value;
        });
    }
    
    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            
            e.target.value = value;
        });
    }
}

function aplicarMascaraDocumento() {
    const tipo = document.getElementById('tipo_documento')?.value;
    const documento = document.getElementById('documento');
    
    if (!tipo || !documento) return;
    
    let value = documento.value.replace(/\D/g, '');
    
    if (tipo === 'CPF') {
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }
    } else if (tipo === 'CNPJ') {
        if (value.length <= 14) {
            value = value.replace(/(\d{2})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1/$2');
            value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
        }
    }
    
    documento.value = value;
}

// Busca automática de CEP
function inicializarBuscaCEP() {
    const cepInput = document.getElementById('cep');
    
    if (cepInput) {
        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            
            if (cep.length === 8) {
                buscarCEP(cep);
            }
        });
    }
}

async function buscarCEP(cep) {
    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await response.json();
        
        if (!data.erro) {
            const ruaInput = document.getElementById('rua');
            const bairroInput = document.getElementById('bairro');
            const cidadeInput = document.getElementById('cidade');
            const estadoInput = document.getElementById('estado');
            const complementoInput = document.getElementById('complemento');
            
            if (ruaInput) ruaInput.value = data.logradouro || '';
            if (bairroInput) bairroInput.value = data.bairro || '';
            if (cidadeInput) cidadeInput.value = data.localidade || '';
            if (estadoInput) estadoInput.value = data.uf || '';
            if (complementoInput) complementoInput.value = data.complemento || '';
        } else {
            mostrarMensagem('CEP não encontrado', 'error');
        }
    } catch (error) {
        console.error('Erro ao buscar CEP:', error);
        mostrarMensagem('Erro ao buscar CEP', 'error');
    }
}

// Busca em tempo real
function inicializarBusca() {
    const searchInput = document.querySelector('.search-input');
    const searchForm = document.querySelector('.search-form');
    
    if (searchInput && searchForm) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchForm.submit();
            }, 500);
        });
        
        // Enter para buscar imediatamente
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    }
}

// Modal de detalhes
function inicializarModal() {
    const modal = document.getElementById('modal-detalhes');
    const closeBtn = modal?.querySelector('.modal-close');
    const detalhesBtns = document.querySelectorAll('.btn-detalhes');
    
    if (!modal || !closeBtn) return;
    
    // Fechar modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    // Fechar ao clicar fora
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Abrir modal com detalhes
    detalhesBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            if (userId) {
                carregarDetalhesUsuario(userId);
            }
        });
    });
}

async function carregarDetalhesUsuario(userId) {
    try {
        console.log('Carregando detalhes do usuário ID:', userId);
        
        const response = await fetch(`buscar_usuario.php?id=${userId}`, {
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        const modalBody = document.getElementById('modal-body');
        if (modalBody) {
            modalBody.innerHTML = criarHTMLDetalhes(data);
            
            // Adicionar event listeners
            const formEditar = document.getElementById('form-editar-usuario');
            if (formEditar) {
                formEditar.addEventListener('submit', function(e) {
                    e.preventDefault();
                    salvarEdicao(e);
                });
            }
        }
        
        const modal = document.getElementById('modal-detalhes');
        if (modal) {
            modal.style.display = 'block';
        }
        
    } catch (error) {
        console.error('Erro ao carregar detalhes:', error);
        mostrarMensagem('Erro ao carregar detalhes do usuário: ' + error.message, 'error');
    }
}

// Criar HTML de detalhes
function criarHTMLDetalhes(usuario) {
    return `
        <div class="user-details-container">
            <!-- Cabeçalho com Ações -->
            <div class="details-header">
                <div class="user-avatar">
                    <i class="fas fa-building"></i>
                </div>
                <div class="user-info">
                    <h3>${usuario.nome_empresa || 'Não informado'}</h3>
                    <p>${usuario.nome_proprietario || 'Não informado'} • ${usuario.email || 'Não informado'}</p>
                </div>
                <div class="header-actions">
                    <button class="btn-whatsapp" onclick="abrirModalWhatsApp(${usuario.id})">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </button>
                    <button class="btn-primary" onclick="habilitarEdicao()">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                </div>
            </div>

            <form method="POST" id="form-editar-usuario">
                <input type="hidden" name="editar_usuario" value="1">
                <input type="hidden" name="usuario_id" value="${usuario.id}">
                
                <!-- Dados da Empresa -->
                <div class="details-section">
                    <h4><i class="fas fa-building"></i> Dados da Empresa</h4>
                    <div class="user-details-grid">
                        <div class="form-group">
                            <label>Nome da Empresa *</label>
                            <input type="text" name="nome_empresa" value="${usuario.nome_empresa || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Proprietário *</label>
                            <input type="text" name="nome_proprietario" value="${usuario.nome_proprietario || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Tipo Documento *</label>
                            <select name="tipo_documento" class="form-input" disabled required>
                                <option value="">Selecione...</option>
                                <option value="CPF" ${usuario.tipo_documento === 'CPF' ? 'selected' : ''}>CPF</option>
                                <option value="CNPJ" ${usuario.tipo_documento === 'CNPJ' ? 'selected' : ''}>CNPJ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Documento *</label>
                            <input type="text" name="documento" value="${usuario.documento || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Quantidade de Usuários *</label>
                            <input type="number" name="quantidade_usuarios" value="${usuario.quantidade_usuarios || 1}" 
                                   min="1" max="50" class="form-input" disabled required>
                        </div>
                    </div>
                </div>

                <!-- Contato -->
                <div class="details-section">
                    <h4><i class="fas fa-address-book"></i> Contato</h4>
                    <div class="user-details-grid">
                        <div class="form-group">
                            <label>E-mail *</label>
                            <input type="email" name="email" value="${usuario.email || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Telefone *</label>
                            <input type="text" name="telefone" value="${usuario.telefone || ''}" 
                                   class="form-input" disabled required>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="details-section">
                    <h4><i class="fas fa-map-marker-alt"></i> Endereço</h4>
                    <div class="user-details-grid">
                        <div class="form-group">
                            <label>CEP *</label>
                            <input type="text" name="cep" value="${usuario.cep || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Rua *</label>
                            <input type="text" name="rua" value="${usuario.rua || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Número *</label>
                            <input type="text" name="numero" value="${usuario.numero || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Bairro *</label>
                            <input type="text" name="bairro" value="${usuario.bairro || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Cidade *</label>
                            <input type="text" name="cidade" value="${usuario.cidade || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Estado *</label>
                            <input type="text" name="estado" value="${usuario.estado || ''}" 
                                   class="form-input" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento" value="${usuario.complemento || ''}" 
                                   class="form-input" disabled>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="details-section">
                    <h4><i class="fas fa-sticky-note"></i> Observações</h4>
                    <div class="form-group">
                        <textarea name="observacoes" class="form-input" rows="4" 
                                  placeholder="Observações sobre o usuário...">${usuario.observacoes || ''}</textarea>
                    </div>
                </div>

                <!-- Ações de Edição -->
                <div class="form-actions" id="edicao-actions" style="display: none;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <button type="button" class="btn-secondary" onclick="cancelarEdicao()">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    `;
}

// Habilitar edição
function habilitarEdicao() {
    const inputs = document.querySelectorAll('#form-editar-usuario .form-input');
    const actions = document.getElementById('edicao-actions');
    
    inputs.forEach(input => {
        input.disabled = false;
    });
    
    if (actions) {
        actions.style.display = 'flex';
    }
    
    // Aplicar máscaras nos campos habilitados
    aplicarMascarasEdicao();
    
    // Focar no primeiro campo
    if (inputs.length > 0) {
        inputs[0].focus();
    }
}

// Cancelar edição
function cancelarEdicao() {
    const modal = document.getElementById('modal-detalhes');
    if (modal) {
        modal.style.display = 'none';
    }
    // Recarregar para voltar aos dados originais
    setTimeout(() => {
        location.reload();
    }, 300);
}

//  Salvar edição
async function salvarEdicao(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        const response = await fetch('admin_dashboard.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        });
        
        if (response.ok) {
            mostrarMensagem('Dados atualizados com sucesso!', 'success');
            // Fechar modal e recarregar
            setTimeout(() => {
                const modal = document.getElementById('modal-detalhes');
                if (modal) {
                    modal.style.display = 'none';
                }
                location.reload();
            }, 1500);
        } else {
            throw new Error('Erro ao salvar alterações');
        }
    } catch (error) {
        console.error('Erro ao salvar edição:', error);
        mostrarMensagem('Erro ao salvar alterações: ' + error.message, 'error');
    }
}

// WhatsApp
function abrirModalWhatsApp(userId) {
    // Buscar dados completos do usuário
    fetch(`buscar_usuario.php?id=${userId}`, {
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(usuario => {
        const modalWhatsApp = document.getElementById('modal-whatsapp');
        const whatsappDetails = document.getElementById('whatsapp-details');
        
        if (!modalWhatsApp || !whatsappDetails) return;
        
        whatsappDetails.innerHTML = `
            <div class="user-info-whatsapp">
                <div class="info-item">
                    <strong>Empresa:</strong> ${usuario.nome_empresa || 'Não informado'}
                </div>
                <div class="info-item">
                    <strong>Telefone:</strong> ${usuario.telefone || 'Não informado'}
                </div>
                <div class="info-item">
                    <strong>E-mail:</strong> ${usuario.email || 'Não informado'}
                </div>
            </div>
            <div class="alert alert-info" style="margin-top: 15px;">
                <i class="fas fa-shield-alt"></i>
                As credenciais de acesso serão enviadas via WhatsApp.
            </div>
        `;
        
        // Configurar botão de confirmação
        const confirmarBtn = document.getElementById('confirmar-whatsapp');
        if (confirmarBtn) {
            confirmarBtn.onclick = function() {
                enviarWhatsApp(usuario.telefone, usuario.nome_empresa, usuario.email, 'SuaSenha123');
                modalWhatsApp.style.display = 'none';
            };
        }
        
        modalWhatsApp.style.display = 'block';
    })
    .catch(error => {
        console.error('Erro ao carregar dados para WhatsApp:', error);
        mostrarMensagem('Erro ao carregar dados do usuário', 'error');
    });
}

// Enviar WhatsApp
function enviarWhatsApp(telefone, nomeEmpresa, email, senha) {
    // ... código anterior ...
    
    // Mensagem personalizada atualizada
    const mensagem = `🚗 *Bem-vindo ao Sistema CarTech!* 🚗

🎉 *Parabéns, sua empresa foi cadastrada com sucesso!*

📋 *Seus dados de acesso:*
🏢 *Empresa:* ${nomeEmpresa}
📧 *E-mail:* ${email}
🔑 *Senha Temporária:* ${senha}

🌐 *Acesse nosso sistema:*
http://cartech-laragon.test/LOGIN/login.php

⚠️ *Importante:*
- Esta é uma senha temporária
- *Você deverá alterar a senha no primeiro acesso*
- Mantenha seus dados confidenciais

📞 *Dúvidas?* Entre em contato conosco.

*Atenciosamente,*
*Equipe CarTech*`;

    // Codifica a mensagem para URL
    const mensagemCodificada = encodeURIComponent(mensagem);
    
    // Cria o link do WhatsApp
    const urlWhatsApp = `https://wa.me/${telefoneFormatado}?text=${mensagemCodificada}`;
    
    // Abre em nova aba
    window.open(urlWhatsApp, '_blank');
}

// Aplicar máscaras na edição
function aplicarMascarasEdicao() {
    const telefoneInput = document.querySelector('input[name="telefone"]');
    const cepInput = document.querySelector('input[name="cep"]');
    const documentoInput = document.querySelector('input[name="documento"]');
    
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    }
    
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
        
        // Buscar CEP automaticamente
        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                buscarCEP(cep);
            }
        });
    }
    
    if (documentoInput) {
        documentoInput.addEventListener('input', function() {
            const tipoDocumento = document.querySelector('select[name="tipo_documento"]');
            aplicarMascaraDocumentoEdicao(tipoDocumento?.value, this);
        });
    }
    
    const tipoDocumento = document.querySelector('select[name="tipo_documento"]');
    if (tipoDocumento) {
        tipoDocumento.addEventListener('change', function() {
            const documentoInput = document.querySelector('input[name="documento"]');
            aplicarMascaraDocumentoEdicao(this.value, documentoInput);
        });
    }
}

// Aplicar máscara de documento na edição
function aplicarMascaraDocumentoEdicao(tipo, documentoInput) {
    if (!tipo || !documentoInput) return;
    
    let value = documentoInput.value.replace(/\D/g, '');
    
    if (tipo === 'CPF') {
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }
    } else if (tipo === 'CNPJ') {
        if (value.length <= 14) {
            value = value.replace(/(\d{2})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1/$2');
            value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
        }
    }
    
    documentoInput.value = value;
}

// WhatsApp Modal
function inicializarWhatsApp() {
    const modalWhatsApp = document.getElementById('modal-whatsapp');
    const closeBtn = modalWhatsApp?.querySelector('.modal-close');
    const cancelarBtn = document.getElementById('cancelar-whatsapp');
    
    if (!modalWhatsApp || !closeBtn || !cancelarBtn) return;
    
    // Fechar modal
    closeBtn.addEventListener('click', function() {
        modalWhatsApp.style.display = 'none';
    });
    
    cancelarBtn.addEventListener('click', function() {
        modalWhatsApp.style.display = 'none';
    });
    
    // Fechar ao clicar fora
    window.addEventListener('click', function(e) {
        if (e.target === modalWhatsApp) {
            modalWhatsApp.style.display = 'none';
        }
    });
}

// Mensagens
function mostrarMensagem(mensagem, tipo = 'error') {
    const alertClass = tipo === 'error' ? 'alert-error' : 'alert-success';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${tipo === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
        ${mensagem}
    `;
    
    const adminContent = document.querySelector('.admin-content');
    if (adminContent) {
        adminContent.insertBefore(alertDiv, adminContent.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// Validação do formulário
const formCriarUsuario = document.getElementById('form-criar-usuario');
if (formCriarUsuario) {
    formCriarUsuario.addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let valid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.style.borderColor = '#ef4444';
            } else {
                field.style.borderColor = '#404040';
            }
        });
        
        if (!valid) {
            e.preventDefault();
            mostrarMensagem('Preencha todos os campos obrigatórios', 'error');
        }
    });
}

// Adicionar CSS para animações se não existir
if (!document.querySelector('#admin-animations')) {
    const estiloAnimacoes = document.createElement('style');
    estiloAnimacoes.id = 'admin-animations';
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
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
    `;
    document.head.appendChild(estiloAnimacoes);
}