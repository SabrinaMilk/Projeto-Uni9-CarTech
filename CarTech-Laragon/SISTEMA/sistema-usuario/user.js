// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Carregado - Inicializando sistema...');
    inicializarSistema();
    inicializarAgenda();
});

function inicializarSistema() {
    console.log('Inicializando sistema...');
    
       // Inicializar máscaras e validações
    inicializarMascaras();
    
    // Navegação do menu
    const menuItems = document.querySelectorAll('.menu li[data-section]');
    const sections = document.querySelectorAll('.section');
    
    console.log('Itens do menu encontrados:', menuItems.length);
    console.log('Seções encontradas:', sections.length);
    
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const sectionId = this.getAttribute('data-section');
            console.log('Clicou no menu:', sectionId);
            showSection(sectionId);
        });
    });

    // Modal de Nova OS - CORREÇÃO AQUI
    const novaOSBtn = document.getElementById('novaOSBtn');
    if (novaOSBtn) {
        novaOSBtn.addEventListener('click', abrirModalOS);
        console.log('Botão Nova OS configurado');
    }
    
    // Formulário de OS - CORREÇÃO CRÍTICA AQUI
    const osForm = document.getElementById('osForm');
    if (osForm) {
        osForm.addEventListener('submit', handleSubmitOS);
        console.log('Formulário OS configurado');
    }
    
    // Tabs do formulário
    inicializarTabs();
    
    // Previsão de reparo
    const previsaoReparo = document.getElementById('previsao_reparo');
    if (previsaoReparo) {
        previsaoReparo.addEventListener('change', function() {
            const dataEspecificaContainer = document.getElementById('data_especifica_container');
            if (this.value === 'data_especifica') {
                dataEspecificaContainer.style.display = 'block';
            } else {
                dataEspecificaContainer.style.display = 'none';
            }
        });
    }

    // Fechar modais
    document.querySelectorAll('.close-modal').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    // Pesquisa e filtros
    const pesquisaOS = document.getElementById('pesquisaOS');
    const filtroStatus = document.getElementById('filtroStatus');
    
    if (pesquisaOS) {
        pesquisaOS.addEventListener('input', filtrarOS);
    }
    
    if (filtroStatus) {
        filtroStatus.addEventListener('change', filtrarOS);
    }

    // Configurar botões da agenda
    inicializarBotoesAgenda();

    console.log('Sistema inicializado com sucesso!');
}
    // Modal de Nova OS
    const novaOSBtn = document.getElementById('novaOSBtn');
    if (novaOSBtn) {
        novaOSBtn.addEventListener('click', abrirModalOS);
        console.log('Botão Nova OS configurado');
    }
    
    // Tabs do formulário
    inicializarTabs();
    
    // Previsão de reparo
    const previsaoReparo = document.getElementById('previsao_reparo');
    if (previsaoReparo) {
        previsaoReparo.addEventListener('change', function() {
            const dataEspecificaContainer = document.getElementById('data_especifica_container');
            if (this.value === 'data_especifica') {
                dataEspecificaContainer.style.display = 'block';
            } else {
                dataEspecificaContainer.style.display = 'none';
            }
        });
    }

    // Fechar modais
    document.querySelectorAll('.close-modal').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    // Pesquisa e filtros
    const pesquisaOS = document.getElementById('pesquisaOS');
    const filtroStatus = document.getElementById('filtroStatus');
    
    if (pesquisaOS) {
        pesquisaOS.addEventListener('input', filtrarOS);
    }
    
    if (filtroStatus) {
        filtroStatus.addEventListener('change', filtrarOS);
    }

    // Configurar botões da agenda
    inicializarBotoesAgenda();

    console.log('Sistema inicializado com sucesso!');

   function handleSubmitOS(e) {
    e.preventDefault();
    
    console.log('Enviando formulário OS...');
    
    // Validar formulário antes do envio
    if (!validarFormularioOS()) {
        return;
    }
    
    const formData = new FormData(document.getElementById('osForm'));
    
    // Mostrar loading
    showNotification('Cadastrando OS...', 'info');
    
    // Enviar via AJAX
    fetch('processa_os.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            fecharModalOS();
            
            // Limpar formulário
            document.getElementById('osForm').reset();
            
            // Atualizar a tabela de OS
            adicionarOSNaTabela(data.os);
            
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao cadastrar OS', 'error');
    });
}

function adicionarOSNaTabela(os) {
    const tbody = document.querySelector('#ordensServicoTable tbody');
    if (!tbody) return;
    
    const novaLinha = document.createElement('tr');
    novaLinha.innerHTML = `
        <td><strong>${os.numero_os}</strong></td>
        <td>${formatarDataHora(os.data_cadastro)}</td>
        <td><span class="uppercase">${os.placa}</span></td>
        <td>${os.modelo}</td>
        <td>${os.ano}</td>
        <td>${os.nome_cliente}</td>
        <td><span class="status-badge status-${os.status}">${formatarStatus(os.status)}</span></td>
        <td>
            ${os.previsao_reparo === 'indefinida' ? 
                '<span class="previsao-indefinida">Indefinida</span>' : 
                `<span class="previsao-data">${formatarPrevisao(os.previsao_reparo)}</span>`
            }
        </td>
        <td>
            <div class="table-actions">
                <button class="btn-action btn-details" onclick="abrirDetalhesOS('${os.id}')">
                    <i class="fas fa-eye"></i> Detalhes
                </button>
                <button class="btn-action btn-edit" onclick="editarOS('${os.id}')">
                    <i class="fas fa-edit"></i> Editar
                </button>
            </div>
        </td>
    `;
    
    // Adicionar no início da tabela
    tbody.insertBefore(novaLinha, tbody.firstChild);
}
// Função para formatar data/hora no formato brasileiro
function formatarDataHoraBrasil(dataString) {
    const data = new Date(dataString);
    
    // Ajustar para o fuso horário de Brasília (UTC-3)
    const offset = -3 * 60; // Brasília é UTC-3
    const localDate = new Date(data.getTime() + (offset * 60 * 1000));
    
    return localDate.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Atualizar a função existente para usar a nova formatação
function formatarDataHora(dataString) {
    return formatarDataHoraBrasil(dataString);
}
function inicializarTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    console.log('Abas encontradas:', tabButtons.length);
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            console.log('Clicou na aba:', tabId);
            
            // Remover classe active de todos
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Adicionar classe active ao selecionado
            this.classList.add('active');
            const targetTab = document.getElementById(tabId);
            if (targetTab) {
                targetTab.classList.add('active');
            }
        });
    });
}

function inicializarBotoesAgenda() {
    // Botões do calendário
    const prevMonth = document.getElementById('prevMonth');
    const nextMonth = document.getElementById('nextMonth');
    const hojeBtn = document.getElementById('hojeBtn');
    const novaAgendamentoBtn = document.getElementById('novaAgendamentoBtn');
    
    if (prevMonth) prevMonth.addEventListener('click', previousMonth);
    if (nextMonth) nextMonth.addEventListener('click', nextMonth);
    if (hojeBtn) hojeBtn.addEventListener('click', goToToday);
    if (novaAgendamentoBtn) novaAgendamentoBtn.addEventListener('click', abrirModalAgendamento);
}

function showSection(sectionId) {
    console.log('Mostrando seção:', sectionId);
    
    // Esconder todas as seções
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Mostrar seção selecionada
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
        console.log('Seção ativada:', sectionId);
    } else {
        console.error('Seção não encontrada:', sectionId);
    }
    
    // Atualizar menu ativo
    document.querySelectorAll('.menu li').forEach(item => {
        item.classList.remove('active');
        if (item.getAttribute('data-section') === sectionId) {
            item.classList.add('active');
            console.log('Menu ativado:', sectionId);
        }
    });
}

function abrirModalOS() {
    console.log('Abrindo modal OS');
    const modal = document.getElementById('cadastroOSModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

function fecharModalOS() {
    const modal = document.getElementById('cadastroOSModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Funções para os botões de ação nas tabelas
function abrirDetalhesOS(osId) {
    console.log('Abrindo detalhes da OS:', osId);
    
    // Dados de exemplo - na prática viriam do servidor
    const os = {
        id: osId,
        numero_os: 'OS20241215001',
        data_cadastro: '2024-12-15 10:30:00',
        placa: 'ABC1D23',
        marca: 'Volkswagen',
        modelo: 'Gol',
        ano: '2022',
        cor: 'Prata',
        nome_cliente: 'João Silva',
        cpf_cliente: '123.456.789-00',
        telefone_cliente: '(11) 99999-9999',
        email_cliente: 'joao@email.com',
        cep: '01234-567',
        rua: 'Rua das Flores',
        numero_endereco: '123',
        bairro: 'Centro',
        cidade: 'São Paulo',
        relato_cliente: 'Veículo apresentando barulho na suspensão dianteira e freios fracos.',
        status: 'diagnostico',
        previsao_reparo: '3_dias',
        diagnostico: 'Amortecedores dianteiros com vazamento de óleo. Pastilhas de freio gastas.',
        observacoes: 'Cliente solicitou orçamento antes da execução do serviço.',
        valor_orcamento: 850.00
    };
    
    const detalhesContent = document.getElementById('detalhesOSContent');
    if (detalhesContent) {
        detalhesContent.innerHTML = criarHTMLDetalhesOS(os);
    }
    
    const modal = document.getElementById('detalhesOSModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

function criarHTMLDetalhesOS(os) {
    return `
        <div class="detalhes-container">
            <div class="detalhes-header">
                <h2 class="detalhes-title">Ordem de Serviço #${os.numero_os}</h2>
                <div class="detalhes-meta">
                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <span class="status-badge status-${os.status}">${formatarStatus(os.status)}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Previsão</span>
                        <span class="previsao-data">${formatarPrevisao(os.previsao_reparo)}</span>
                    </div>
                </div>
            </div>
            
            <div class="detalhes-grid">
                <!-- Dados do Veículo -->
                <div class="detalhes-section">
                    <h3><i class="fas fa-car"></i> Dados do Veículo</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Placa:</label>
                            <span class="uppercase">${os.placa}</span>
                        </div>
                        <div class="info-item">
                            <label>Marca/Modelo:</label>
                            <span>${os.marca} ${os.modelo}</span>
                        </div>
                        <div class="info-item">
                            <label>Ano/Cor:</label>
                            <span>${os.ano} • ${os.cor}</span>
                        </div>
                    </div>
                </div>

                <!-- Dados do Cliente -->
                <div class="detalhes-section">
                    <h3><i class="fas fa-user"></i> Dados do Cliente</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nome:</label>
                            <span>${os.nome_cliente}</span>
                        </div>
                        <div class="info-item">
                            <label>CPF:</label>
                            <span>${os.cpf_cliente}</span>
                        </div>
                        <div class="info-item">
                            <label>Telefone:</label>
                            <span>${os.telefone_cliente}</span>
                        </div>
                        ${os.email_cliente ? `
                        <div class="info-item">
                            <label>Email:</label>
                            <span>${os.email_cliente}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Endereço -->
                <div class="detalhes-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Endereço</h3>
                    <div class="info-grid">
                        ${os.rua ? `
                        <div class="info-item">
                            <label>Endereço:</label>
                            <span>${os.rua}, ${os.numero_endereco}</span>
                        </div>
                        ` : ''}
                        ${os.bairro ? `
                        <div class="info-item">
                            <label>Bairro:</label>
                            <span>${os.bairro}</span>
                        </div>
                        ` : ''}
                        ${os.cidade ? `
                        <div class="info-item">
                            <label>Cidade:</label>
                            <span>${os.cidade}</span>
                        </div>
                        ` : ''}
                        ${os.cep ? `
                        <div class="info-item">
                            <label>CEP:</label>
                            <span>${os.cep}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Serviço -->
                <div class="detalhes-section">
                    <h3><i class="fas fa-tools"></i> Informações do Serviço</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Data Cadastro:</label>
                            <span>${formatarDataHora(os.data_cadastro)}</span>
                        </div>
                        <div class="info-item">
                            <label>Previsão Reparo:</label>
                            <span class="previsao-data">${formatarPrevisao(os.previsao_reparo)}</span>
                        </div>
                    </div>
                </div>

                <!-- Relato e Diagnóstico -->
                <div class="detalhes-section full-width">
                    <h3><i class="fas fa-clipboard"></i> Relato e Diagnóstico</h3>
                    <div class="info-grid">
                        <div class="info-item full-width">
                            <label>Relato do Cliente:</label>
                            <div class="relato-box">${os.relato_cliente}</div>
                        </div>
                        
                        <div class="info-item full-width">
                            <label>Diagnóstico:</label>
                            <textarea class="diagnostico-textarea" placeholder="Digite o diagnóstico...">${os.diagnostico || ''}</textarea>
                        </div>
                        
                        <div class="info-item full-width">
                            <label>Observações:</label>
                            <textarea class="observacoes-textarea" placeholder="Observações adicionais...">${os.observacoes || ''}</textarea>
                        </div>
                        
                        <div class="info-item">
                            <label>Valor do Orçamento:</label>
                            <input type="number" class="valor-input" value="${os.valor_orcamento || 0}" step="0.01">
                        </div>
                    </div>
                </div>
            </div>

            <div class="detalhes-actions">
                <button class="btn-primary" onclick="salvarDetalhesOS('${os.id}')">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <button class="btn-secondary" onclick="fecharDetalhesOS()">
                    <i class="fas fa-times"></i> Fechar
                </button>
            </div>
        </div>
    `;
}

function formatarStatus(status) {
    const statusMap = {
        'checkup': 'Checkup',
        'diagnostico': 'Diagnóstico',
        'preparando_orcamento': 'Preparando Orçamento',
        'aguardando_aval': 'Aguardando Aval',
        'orcamento_recusado': 'Orçamento Recusado',
        'em_manutencao': 'Em Manutenção',
        'aguardando_peca': 'Aguardando Peça',
        'em_teste': 'Em Teste',
        'reanalise': 'Reanálise',
        'pronto_retirada': 'Pronto Retirada',
        'encerrado': 'Encerrado'
    };
    return statusMap[status] || status;
}

function formatarPrevisao(previsao) {
    if (previsao === 'indefinida') return 'Indefinida';
    
    const previsaoMap = {
        '1_dia': '1 dia',
        '2_dias': '2 dias',
        '3_dias': '3 dias',
        '5_dias': '5 dias',
        '7_dias': '7 dias',
        '15_dias': '15 dias'
    };
    
    return previsaoMap[previsao] || previsao;
}

function formatarDataHora(dataString) {
    return new Date(dataString).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function fecharDetalhesOS() {
    const modal = document.getElementById('detalhesOSModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function salvarDetalhesOS(osId) {
    // Em produção, enviaria para o servidor
    showNotification('Alterações salvas com sucesso!', 'success');
    fecharDetalhesOS();
}

function editarOS(osId) {
    // Em produção, carregaria os dados e abriria o formulário de edição
    abrirModalOS();
    showNotification('Carregando dados da OS...', 'info');
}

function filtrarOS() {
    const termo = document.getElementById('pesquisaOS').value.toLowerCase();
    const filtroStatus = document.getElementById('filtroStatus').value;
    
    const linhas = document.querySelectorAll('#ordensServicoTable tbody tr');
    
    linhas.forEach(linha => {
        const textoLinha = linha.textContent.toLowerCase();
        const status = linha.querySelector('.status-badge')?.className || '';
        
        const matchTermo = textoLinha.includes(termo);
        const matchStatus = !filtroStatus || status.includes(filtroStatus);
        
        linha.style.display = matchTermo && matchStatus ? '' : 'none';
    });
}

function showNotification(message, type = 'success') {
    // Implementação básica de notificação - você pode usar uma biblioteca ou criar um sistema próprio
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    if (type === 'success') {
        notification.style.background = '#10b981';
    } else if (type === 'error') {
        notification.style.background = '#ef4444';
    } else if (type === 'info') {
        notification.style.background = '#3b82f6';
    } else {
        notification.style.background = '#6b7280';
    }
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// ========== FUNÇÕES DA AGENDA ==========
let currentDate = new Date();
let selectedDate = null;
let agendamentos = JSON.parse(localStorage.getItem('agendamentos')) || [];

function inicializarAgenda() {
    console.log('Inicializando agenda...');
    renderCalendar();
    atualizarEstatisticas();
}

function renderCalendar() {
    const calendarGrid = document.getElementById('calendarGrid');
    if (!calendarGrid) return;
    
    calendarGrid.innerHTML = '';
    
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    // Atualizar título do mês
    const currentMonthElement = document.getElementById('currentMonth');
    if (currentMonthElement) {
        currentMonthElement.textContent = 
            currentDate.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
    }
    
    // Primeiro dia do mês
    const firstDay = new Date(year, month, 1);
    // Último dia do mês
    const lastDay = new Date(year, month + 1, 0);
    // Dia da semana do primeiro dia (0 = Domingo, 6 = Sábado)
    const firstDayOfWeek = firstDay.getDay();
    
    // Dias do mês anterior
    const prevMonthLastDay = new Date(year, month, 0).getDate();
    
    // Gerar dias do mês anterior
    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
        const day = prevMonthLastDay - i;
        const date = new Date(year, month - 1, day);
        calendarGrid.appendChild(createDayElement(date, true));
    }
    
    // Gerar dias do mês atual
    for (let day = 1; day <= lastDay.getDate(); day++) {
        const date = new Date(year, month, day);
        calendarGrid.appendChild(createDayElement(date, false));
    }
    
    // Calcular quantos dias do próximo mês precisamos
    const totalCells = 42; // 6 semanas
    const cellsUsed = firstDayOfWeek + lastDay.getDate();
    const nextMonthDays = totalCells - cellsUsed;
    
    // Gerar dias do próximo mês
    for (let day = 1; day <= nextMonthDays; day++) {
        const date = new Date(year, month + 1, day);
        calendarGrid.appendChild(createDayElement(date, true));
    }
    
    atualizarEstatisticas();
}

function createDayElement(date, isOtherMonth) {
    const dayElement = document.createElement('div');
    dayElement.className = 'calendar-day';
    
    if (isOtherMonth) {
        dayElement.classList.add('other-month');
    }
    
    // Verificar se é hoje
    const today = new Date();
    if (date.toDateString() === today.toDateString()) {
        dayElement.classList.add('today');
    }
    
    // Verificar se está selecionado
    if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
        dayElement.classList.add('selected');
    }
    
    // Agendamentos deste dia
    const dayAgendamentos = agendamentos.filter(ag => {
        const agDate = new Date(ag.data);
        return agDate.toDateString() === date.toDateString() && ag.status === 'agendado';
    });
    
    const dayNumber = document.createElement('div');
    dayNumber.className = 'day-number';
    dayNumber.textContent = date.getDate();
    
    const agendamentosContainer = document.createElement('div');
    agendamentosContainer.className = 'day-agendamentos';
    
    // Mostrar até 3 agendamentos no calendário
    dayAgendamentos.slice(0, 3).forEach(agendamento => {
        const badge = document.createElement('div');
        badge.className = `agendamento-badge ${agendamento.servico}`;
        badge.innerHTML = `
            <i class="fas fa-car"></i>
            ${agendamento.placa}
        `;
        agendamentosContainer.appendChild(badge);
    });
    
    // Mostrar contador se houver mais agendamentos
    if (dayAgendamentos.length > 3) {
        const moreBadge = document.createElement('div');
        moreBadge.className = 'agendamento-badge';
        moreBadge.textContent = `+${dayAgendamentos.length - 3}`;
        agendamentosContainer.appendChild(moreBadge);
    }
    
    // Indicador de capacidade
    const capacityIndicator = document.createElement('div');
    capacityIndicator.className = 'capacity-indicator';
    
    if (dayAgendamentos.length === 0) {
        capacityIndicator.classList.add('capacity-low');
    } else if (dayAgendamentos.length <= 3) {
        capacityIndicator.classList.add('capacity-medium');
    } else {
        capacityIndicator.classList.add('capacity-high');
    }
    
    dayElement.appendChild(dayNumber);
    dayElement.appendChild(agendamentosContainer);
    dayElement.appendChild(capacityIndicator);
    
    // Event Listener para selecionar dia
    dayElement.addEventListener('click', () => {
        selectedDate = date;
        renderCalendar();
        showDailySchedule(date);
    });
    
    return dayElement;
}

function showDailySchedule(date) {
    const dailySchedule = document.getElementById('dailySchedule');
    const scheduleList = document.getElementById('scheduleList');
    const selectedDateTitle = document.getElementById('selectedDateTitle');
    const dayScheduleCount = document.getElementById('dayScheduleCount');
    
    if (!dailySchedule || !scheduleList) return;
    
    const dayAgendamentos = agendamentos.filter(ag => {
        const agDate = new Date(ag.data);
        return agDate.toDateString() === date.toDateString() && ag.status === 'agendado';
    }).sort((a, b) => a.hora.localeCompare(b.hora));
    
    selectedDateTitle.textContent = `Agendamentos para ${date.toLocaleDateString('pt-BR')}`;
    dayScheduleCount.textContent = `${dayAgendamentos.length} agendamento${dayAgendamentos.length !== 1 ? 's' : ''}`;
    
    scheduleList.innerHTML = '';
    
    if (dayAgendamentos.length === 0) {
        scheduleList.innerHTML = `
            <div style="text-align: center; padding: 40px; color: var(--text-secondary);">
                <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 15px;"></i>
                <p>Nenhum agendamento para esta data</p>
            </div>
        `;
    } else {
        dayAgendamentos.forEach(agendamento => {
            const scheduleItem = document.createElement('div');
            scheduleItem.className = 'schedule-item';
            scheduleItem.innerHTML = `
                <div class="schedule-info">
                    <div class="schedule-time">${agendamento.hora}</div>
                    <div class="schedule-vehicle">
                        <span class="placa">${agendamento.placa}</span>
                        ${agendamento.marca} ${agendamento.modelo}
                    </div>
                    <div class="schedule-client">
                        <i class="fas fa-user"></i> ${agendamento.cliente}
                    </div>
                </div>
                <div class="schedule-service">
                    <span class="service-badge ${agendamento.servico}">
                        ${formatarServico(agendamento.servico)}
                    </span>
                </div>
                <div class="schedule-actions">
                    <button class="btn-schedule-action btn-concluir" onclick="concluirAgendamento('${agendamento.id}')">
                        <i class="fas fa-check"></i> Concluir
                    </button>
                    <button class="btn-schedule-action btn-cancelar" onclick="cancelarAgendamento('${agendamento.id}')">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            `;
            scheduleList.appendChild(scheduleItem);
        });
    }
    
    dailySchedule.style.display = 'block';
}

function abrirModalAgendamento() {
    console.log('Abrindo modal de agendamento');
    // Implementar abertura do modal de agendamento
    showNotification('Funcionalidade de agendamento em desenvolvimento', 'info');
}

function formatarServico(servico) {
    const servicosMap = {
        'revisao': 'Revisão',
        'troca_oleo': 'Troca de Óleo',
        'alinhamento': 'Alinhamento',
        'freios': 'Freios',
        'suspensao': 'Suspensão',
        'eletrica': 'Elétrica',
        'ar_condicionado': 'Ar Condicionado',
        'outro': 'Outro'
    };
    
    return servicosMap[servico] || servico;
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

function goToToday() {
    currentDate = new Date();
    selectedDate = new Date();
    renderCalendar();
    showDailySchedule(selectedDate);
}

function atualizarEstatisticas() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    const agendamentosMes = agendamentos.filter(ag => {
        const agDate = new Date(ag.data);
        return agDate.getFullYear() === year && 
               agDate.getMonth() === month && 
               ag.status === 'agendado';
    });
    
    const totalAgendamentosElement = document.getElementById('totalAgendamentos');
    if (totalAgendamentosElement) {
        totalAgendamentosElement.textContent = agendamentosMes.length;
    }
}

// Funções globais para acesso via HTML
window.fecharModalOS = fecharModalOS;
window.fecharDetalhesOS = fecharDetalhesOS;
window.abrirDetalhesOS = abrirDetalhesOS;
window.showSection = showSection;
window.salvarDetalhesOS = salvarDetalhesOS;
window.editarOS = editarOS;
window.concluirAgendamento = function(id) {
    showNotification('Agendamento concluído!', 'success');
};
window.cancelarAgendamento = function(id) {
    showNotification('Agendamento cancelado!', 'success');
};

// Adicionar CSS para animações
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .capacity-indicator {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .capacity-low { background: #10b981; }
    .capacity-medium { background: #f59e0b; }
    .capacity-high { background: #ef4444; }
`;
document.head.appendChild(style);

// ==================================================
// MÁSCARAS E VALIDAÇÕES
// ==================================================

function inicializarMascaras() {
    console.log('Inicializando máscaras...');
    
    // Máscara para CPF
    const cpfInput = document.getElementById('cpf_cliente');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            this.value = mascaraCPF(this.value);
        });
        
        cpfInput.addEventListener('blur', function() {
            if (this.value && !validarCPF(this.value)) {
                showNotification('CPF inválido!', 'error');
                this.focus();
            }
        });
    }
    
    // Máscara para Telefone
    const telefoneInput = document.getElementById('telefone_cliente');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            this.value = mascaraTelefone(this.value);
        });
    }
    
    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            this.value = mascaraCEP(this.value);
        });
        
        cepInput.addEventListener('blur', function() {
            if (this.value.length === 9) {
                buscarEnderecoPorCEP(this.value);
            }
        });
    }
    
    // Máscara para Placa (formato Mercosul)
    const placaInput = document.getElementById('placa');
    if (placaInput) {
        placaInput.addEventListener('input', function(e) {
            this.value = mascaraPlaca(this.value);
        });
    }
    
    // Limitar ano do veículo
    const anoInput = document.getElementById('ano');
    if (anoInput) {
        anoInput.addEventListener('input', function(e) {
            if (this.value.length > 4) {
                this.value = this.value.slice(0, 4);
            }
        });
    }
}

// Máscara para CPF
function mascaraCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    cpf = cpf.substring(0, 11);
    
    if (cpf.length <= 3) {
        return cpf;
    } else if (cpf.length <= 6) {
        return cpf.replace(/(\d{3})(\d{1,3})/, '$1.$2');
    } else if (cpf.length <= 9) {
        return cpf.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
    } else {
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
    }
}

// Validar CPF
function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    
    if (cpf.length !== 11) return false;
    
    // Verificar se todos os dígitos são iguais
    if (/^(\d)\1+$/.test(cpf)) return false;
    
    // Validar primeiro dígito verificador
    let soma = 0;
    for (let i = 0; i < 9; i++) {
        soma += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let resto = soma % 11;
    let digito1 = resto < 2 ? 0 : 11 - resto;
    
    if (digito1 !== parseInt(cpf.charAt(9))) return false;
    
    // Validar segundo dígito verificador
    soma = 0;
    for (let i = 0; i < 10; i++) {
        soma += parseInt(cpf.charAt(i)) * (11 - i);
    }
    resto = soma % 11;
    let digito2 = resto < 2 ? 0 : 11 - resto;
    
    return digito2 === parseInt(cpf.charAt(10));
}

// Máscara para Telefone
function mascaraTelefone(telefone) {
    telefone = telefone.replace(/\D/g, '');
    telefone = telefone.substring(0, 11);
    
    if (telefone.length === 0) return '';
    
    if (telefone.length <= 2) {
        return `(${telefone}`;
    } else if (telefone.length <= 6) {
        return `(${telefone.substring(0, 2)}) ${telefone.substring(2)}`;
    } else if (telefone.length <= 10) {
        return `(${telefone.substring(0, 2)}) ${telefone.substring(2, 6)}-${telefone.substring(6)}`;
    } else {
        return `(${telefone.substring(0, 2)}) ${telefone.substring(2, 7)}-${telefone.substring(7)}`;
    }
}

// Máscara para CEP
function mascaraCEP(cep) {
    cep = cep.replace(/\D/g, '');
    cep = cep.substring(0, 8);
    
    if (cep.length <= 5) {
        return cep;
    } else {
        return cep.replace(/(\d{5})(\d{1,3})/, '$1-$2');
    }
}

// Máscara para Placa (Mercosul)
function mascaraPlaca(placa) {
    placa = placa.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
    placa = placa.substring(0, 7);
    
    if (placa.length <= 3) {
        return placa;
    } else if (placa.length <= 4) {
        return placa.replace(/([A-Za-z]{3})([A-Za-z0-9]{1})/, '$1$2');
    } else {
        return placa.replace(/([A-Za-z]{3})([A-Za-z0-9]{1})([A-Za-z0-9]{2})/, '$1$2$3');
    }
}

// Buscar endereço por CEP
function buscarEnderecoPorCEP(cep) {
    cep = cep.replace(/\D/g, '');
    
    if (cep.length !== 8) return;
    
    showNotification('Buscando endereço...', 'info');
    
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (!data.erro) {
                document.getElementById('rua').value = data.logradouro || '';
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('cidade').value = data.localidade || '';
                showNotification('Endereço preenchido automaticamente!', 'success');
            } else {
                showNotification('CEP não encontrado', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao buscar CEP:', error);
            showNotification('Erro ao buscar CEP', 'error');
        });
}

// Validar formulário antes do envio
function validarFormularioOS() {
    const camposObrigatorios = [
        'placa', 'marca', 'modelo', 'ano', 'nome_cliente', 
        'cpf_cliente', 'telefone_cliente', 'relato_cliente', 'status'
    ];
    
    for (let campo of camposObrigatorios) {
        const input = document.getElementById(campo);
        if (input && !input.value.trim()) {
            showNotification(`Campo ${input.previousElementSibling?.textContent || campo} é obrigatório!`, 'error');
            input.focus();
            return false;
        }
    }
    
    // Validar CPF
    const cpf = document.getElementById('cpf_cliente').value;
    if (cpf && !validarCPF(cpf)) {
        showNotification('CPF inválido!', 'error');
        document.getElementById('cpf_cliente').focus();
        return false;
    }
    
    // Validar ano
    const ano = document.getElementById('ano').value;
    if (ano && (ano < 1950 || ano > new Date().getFullYear() + 1)) {
        showNotification('Ano do veículo inválido!', 'error');
        document.getElementById('ano').focus();
        return false;
    }
    
    return true;
}