<?php

// Configurar timezone para Brasília
date_default_timezone_set('America/Sao_Paulo');

session_start();

// Verificar se está logado como empresa
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'empresa' || $_SESSION['usuario']['logado'] !== true) {
    header('Location: ../../LOGIN/login.php');
    exit;
}

// Conexão com banco para buscar dados da empresa
$host = 'localhost';
$dbname = 'sistema_cartech';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar dados da empresa logada
    $usuario_id = $_SESSION['usuario']['id'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios_empresa WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $empresa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$empresa) {
        // Se não encontrou a empresa, faz logout
        session_destroy();
        header('Location: ../../LOGIN/login.php');
        exit;
    }
    
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados");
}

session_start(); // DEVE SER REFEITO EM SQL PARA SAIR DA SIMULAÇÃO, TIRAR DEPOIS
// Simulação de dados - na prática você conectaria com banco de dados
$ordens_servico = isset($_SESSION['ordens_servico']) ? $_SESSION['ordens_servico'] : [];
$agendamentos = isset($_SESSION['agendamentos']) ? $_SESSION['agendamentos'] : [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarTech - Sistema de Gestão Mecânica</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <h1>Painel da Empresa</h1>
        <div class="user-info">
            Bem-vindo, <?php echo htmlspecialchars($empresa['nome_empresa']); ?>
            <a href="logout.php">Sair</a>
        </div>

</head>

<body>
    <!-- Modal de Detalhes da OS -->
    <div id="detalhesOSModal" class="modal">
        <div class="modal-content large-modal">
            <span class="close-modal">&times;</span>
            <div id="detalhesOSContent">
                <!-- Preenchido via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Cadastro de OS -->
    <div id="cadastroOSModal" class="modal">
        <div class="modal-content large-modal">
            <span class="close-modal">&times;</span>
            <h2><i class="fas fa-clipboard-list"></i> Nova Ordem de Serviço</h2>
            
            <form id="osForm">
    <div class="form-tabs">
        <div class="tab-buttons">
            <button type="button" class="tab-btn active" data-tab="dados-veiculo">Dados do Veículo</button>
            <button type="button" class="tab-btn" data-tab="dados-cliente">Dados do Cliente</button>
            <button type="button" class="tab-btn" data-tab="dados-servico">Serviço</button>
        </div>

        <div class="tab-content active" id="dados-veiculo">
            <div class="form-grid">
                <div class="input-group">
                    <label for="placa">Placa do Veículo *</label>
                    <input type="text" id="placa" name="placa" required class="uppercase" maxlength="7" placeholder="ABC1D23" autocomplete="off" pattern="[A-Za-z]{3}[0-9A-Za-z][0-9A-Za-z]{2}">
                </div>
                
                <div class="input-row">
                    <div class="input-group">
                        <label for="marca">Marca *</label>
                        <input type="text" id="marca" name="marca" required placeholder="Ex: Volkswagen" autocomplete="off">
                    </div>
                    <div class="input-group">
                        <label for="modelo">Modelo *</label>
                        <input type="text" id="modelo" name="modelo" required placeholder="Ex: Gol" autocomplete="off">
                    </div>
                </div>
                
                <div class="input-row">
                    <div class="input-group">
                        <label for="ano">Ano *</label>
                        <input type="number" id="ano" name="ano" required min="1950" max="2024" placeholder="2023" autocomplete="off">
                    </div>
                    <div class="input-group">
                        <label for="cor">Cor</label>
                        <input type="text" id="cor" name="cor" placeholder="Ex: Prata" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="dados-cliente">
            <div class="form-grid">
                <div class="input-group">
                    <label for="nome_cliente">Nome Completo *</label>
                    <input type="text" id="nome_cliente" name="nome_cliente" required placeholder="Nome do cliente" autocomplete="off">
                </div>
                
                <div class="input-row">
                    <div class="input-group">
                        <label for="cpf_cliente">CPF *</label>
                        <input type="text" id="cpf_cliente" name="cpf_cliente" required class="cpf-mask" placeholder="000.000.000-00" autocomplete="off" maxlength="14">
                    </div>
                    <div class="input-group">
                        <label for="telefone_cliente">Telefone *</label>
                        <input type="tel" id="telefone_cliente" name="telefone_cliente" required placeholder="(11) 99999-9999" autocomplete="off" maxlength="15">
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="email_cliente">Email</label>
                    <input type="email" id="email_cliente" name="email_cliente" placeholder="cliente@email.com" autocomplete="off">
                </div>
                
                <div class="input-row">
                    <div class="input-group">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" class="cep-mask" placeholder="00000-000" autocomplete="off" maxlength="9">
                    </div>
                    <div class="input-group">
                        <label for="numero_endereco">Número</label>
                        <input type="text" id="numero_endereco" name="numero_endereco" placeholder="123" autocomplete="off" maxlength="9">
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="rua">Rua</label>
                    <input type="text" id="rua" name="rua" placeholder="Rua das Flores" autocomplete="off">
                </div>
                
                <div class="input-row">
                    <div class="input-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro" placeholder="Centro" autocomplete="off">
                    </div>
                    <div class="input-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" placeholder="São Paulo" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="dados-servico">
            <div class="form-grid">
                <div class="input-group">
                    <label for="relato_cliente">Relato do Cliente *</label>
                    <textarea id="relato_cliente" name="relato_cliente" required rows="4" placeholder="Descreva o problema relatado pelo cliente..." autocomplete="off"></textarea>
                </div>
                
                <div class="input-row">
                    <div class="input-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required autocomplete="off">
                            <option value="">Selecione o status</option>
                            <option value="checkup">Checkup</option>
                            <option value="diagnostico">Diagnóstico</option>
                            <option value="preparando_orcamento">Preparando Orçamento</option>
                            <option value="aguardando_aval">Aguardando Aval Cliente</option>
                            <option value="orcamento_recusado">Orçamento Recusado</option>
                            <option value="em_manutencao">Em Manutenção</option>
                            <option value="aguardando_peca">Aguardando Peça</option>
                            <option value="em_teste">Em Teste</option>
                            <option value="reanalise">Reanálise</option>
                            <option value="pronto_retirada">Pronto para Retirada</option>
                            <option value="encerrado">Encerrado</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="previsao_reparo">Previsão para Reparo</label>
                        <select id="previsao_reparo" name="previsao_reparo" autocomplete="off">
                            <option value="indefinida">Indefinida</option>
                            <option value="1_dia">1 dia</option>
                            <option value="2_dias">2 dias</option>
                            <option value="3_dias">3 dias</option>
                            <option value="5_dias">5 dias</option>
                            <option value="7_dias">7 dias</option>
                            <option value="15_dias">15 dias</option>
                            <option value="data_especifica">Data Específica</option>
                        </select>
                    </div>
                </div>
                
                <div class="input-group" id="data_especifica_container" style="display: none;">
                    <label for="data_especifica">Data Específica</label>
                    <input type="date" id="data_especifica" name="data_especifica" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="fecharModalOS()">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Cadastrar OS
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="container">
        <nav class="menu">
            <div class="logo">
                <i class="fas fa-tools"></i>
                <h2>CarTech</h2>
            </div>
            <div id="profile-info">
                <span id="user-name">Usuário</span>
            </div>
            <ul>
                <li data-section="dashboard" data-tooltip="Dashboard">
                    <i class="fas fa-chart-bar"></i>
                    <span>Dashboard</span>
                </li>
                <li data-section="cadastro-os" data-tooltip="Cadastro de OS">
                    <i class="fas fa-plus-circle"></i>
                    <span>Cadastro de OS</span>
                </li>
                <li data-section="ordens-servico" data-tooltip="Ordens de Serviço">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Ordens de Serviço</span>
                </li>
                <li data-section="agenda" data-tooltip="Agenda">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Agenda</span>
                </li>
            </ul>
            <ul style="margin-top: auto;">
    <li>
        <a href="../../LOGIN/login.php" class="nav-item logout" data-section="sair" data-tooltip="Sair">
            <i class="fas fa-sign-out-alt"></i>
            <span class="nav-text">Sair</span>
        </a>
    </li>
</ul>
        </nav>
        
        <main class="content">
            <!-- DASHBOARD -->
            <section id="dashboard" class="section active">
                <div class="header-dashboard">
                    <h1>Dashboard CarTech</h1>
                    <div class="data-info">Atualizado em: <?php echo date('d/m/Y H:i'); ?></div>
                </div>
                
                <div class="dashboard-grid">
                    <div class="metric-card">
                        <div class="metric-icon gold">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="metric-info">
                            <h3>OS em Andamento</h3>
                            <span class="metric-value" id="os-andamento">0</span>
                            <span class="metric-trend up">+2 hoje</span>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon blue">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="metric-info">
                            <h3>OS Concluídas</h3>
                            <span class="metric-value" id="os-concluidas">0</span>
                            <span class="metric-trend">este mês</span>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon gold">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Aguardando Peças</h3>
                            <span class="metric-value" id="os-pecas">0</span>
                            <span class="metric-trend down">-1</span>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon blue">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Veículos no Mês</h3>
                            <span class="metric-value" id="veiculos-mes">0</span>
                            <span class="metric-trend up">+5</span>
                        </div>
                    </div>
                </div>

                <!-- Status das OS em Tempo Real -->
                <div class="status-grid">
                    <div class="status-card">
                        <h3>Distribuição por Status</h3>
                        <div class="status-list" id="status-distribution">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>
                    
                    <div class="recent-activity">
                        <h3>Atividade Recente</h3>
                        <div class="activity-list" id="recent-activities">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>
                </div>
            </section>

            <!-- CADASTRO DE OS -->
            <section id="cadastro-os" class="section">
                <div class="section-header">
                    <h1>Cadastro de Ordem de Serviço</h1>
                    <button id="novaOSBtn" class="btn-primary">
                        <i class="fas fa-plus-circle"></i> Nova OS
                    </button>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <h3>Como cadastrar uma OS?</h3>
                        <p>Clique em "Nova OS" para abrir o formulário de cadastro. Preencha todas as informações do veículo, cliente e serviço solicitado.</p>
                    </div>
                </div>

                <!-- Estatísticas Rápidas -->
                <div class="quick-stats">
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo count(array_filter($ordens_servico, function($os) { return $os['status'] === 'checkup'; })); ?></span>
                        <span class="stat-label">Em Checkup</span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo count(array_filter($ordens_servico, function($os) { return $os['status'] === 'diagnostico'; })); ?></span>
                        <span class="stat-label">Em Diagnóstico</span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo count(array_filter($ordens_servico, function($os) { return $os['status'] === 'em_manutencao'; })); ?></span>
                        <span class="stat-label">Em Manutenção</span>
                    </div>
                </div>
            </section>

            <!-- ORDENS DE SERVIÇO -->
            <section id="ordens-servico" class="section">
                <div class="section-header">
                    <h1>Ordens de Serviço</h1>
                    <div class="table-controls">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="pesquisaOS" placeholder="Pesquisar por placa, cliente...">
                        </div>
                        <select id="filtroStatus">
                            <option value="">Todos os status</option>
                            <option value="checkup">Checkup</option>
                            <option value="diagnostico">Diagnóstico</option>
                            <option value="preparando_orcamento">Preparando Orçamento</option>
                            <option value="aguardando_aval">Aguardando Aval</option>
                            <option value="em_manutencao">Em Manutenção</option>
                            <option value="pronto_retirada">Pronto para Retirada</option>
                        </select>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-wrapper">
                        <table id="ordensServicoTable">
                            <thead>
                                <tr>
                                    <th>N° OS</th>
                                    <th>Data Cadastro</th>
                                    <th>Placa</th>
                                    <th>Modelo</th>
                                    <th>Ano</th>
                                    <th>Cliente</th>
                                    <th>Status</th>
                                    <th>Previsão</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="ordensServicoTableBody">
                                <?php foreach ($ordens_servico as $os): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($os['numero_os']); ?></strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($os['data_cadastro'])); ?></td>
                                    <td><span class="uppercase"><?php echo htmlspecialchars($os['placa']); ?></span></td>
                                    <td><?php echo htmlspecialchars($os['modelo']); ?></td>
                                    <td><?php echo htmlspecialchars($os['ano']); ?></td>
                                    <td><?php echo htmlspecialchars($os['nome_cliente']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $os['status']; ?>">
                                            <?php 
                                            $status_map = [
                                                'checkup' => 'Checkup',
                                                'diagnostico' => 'Diagnóstico',
                                                'preparando_orcamento' => 'Preparando Orçamento',
                                                'aguardando_aval' => 'Aguardando Aval',
                                                'orcamento_recusado' => 'Orçamento Recusado',
                                                'em_manutencao' => 'Em Manutenção',
                                                'aguardando_peca' => 'Aguardando Peça',
                                                'em_teste' => 'Em Teste',
                                                'reanalise' => 'Reanálise',
                                                'pronto_retirada' => 'Pronto Retirada',
                                                'encerrado' => 'Encerrado'
                                            ];
                                            echo $status_map[$os['status']] ?? $os['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($os['previsao_reparo'] === 'indefinida'): ?>
                                            <span class="previsao-indefinida">Indefinida</span>
                                        <?php else: ?>
                                            <span class="previsao-data"><?php echo htmlspecialchars($os['previsao_reparo']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <button class="btn-action btn-details" onclick="abrirDetalhesOS('<?php echo $os['id']; ?>')">
                                                <i class="fas fa-eye"></i> Detalhes
                                            </button>
                                            <button class="btn-action btn-edit" onclick="editarOS('<?php echo $os['id']; ?>')">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- AGENDA -->
            <section id="agenda" class="section">
                <div class="section-header">
                    <h1>Agenda e Calendário</h1>
                    <div class="calendar-controls">
                        <button id="hojeBtn" class="btn-secondary">
                            <i class="fas fa-calendar-day"></i> Hoje
                        </button>
                        <button id="novaAgendamentoBtn" class="btn-primary">
                            <i class="fas fa-plus"></i> Novo Agendamento
                        </button>
                    </div>
                </div>

                <!-- Controles do Calendário -->
                <div class="calendar-header">
                    <button id="prevMonth" class="btn-calendar">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="calendar-title">
                        <h2 id="currentMonth"><?php echo date('F Y'); ?></h2>
                        <span id="calendarStats" class="calendar-stats">
                            <i class="fas fa-car"></i>
                            <span id="totalAgendamentos">0</span> veículos agendados este mês
                        </span>
                    </div>
                    
                    <button id="nextMonth" class="btn-calendar">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Visualização do Calendário -->
                <div class="calendar-view">
                    <div class="calendar-weekdays">
                        <div class="weekday">Dom</div>
                        <div class="weekday">Seg</div>
                        <div class="weekday">Ter</div>
                        <div class="weekday">Qua</div>
                        <div class="weekday">Qui</div>
                        <div class="weekday">Sex</div>
                        <div class="weekday">Sáb</div>
                    </div>

                    <div class="calendar-grid" id="calendarGrid">
                        <!-- Gerado via JavaScript -->
                    </div>
                </div>

                <!-- Lista de Agendamentos do Dia Selecionado -->
                <div class="daily-schedule" id="dailySchedule" style="display: none;">
                    <div class="schedule-header">
                        <h3 id="selectedDateTitle">Agendamentos para </h3>
                        <span class="schedule-count" id="dayScheduleCount">0 agendamentos</span>
                    </div>
                    <div class="schedule-list" id="scheduleList">
                        <!-- Lista de agendamentos do dia -->
                    </div>
                </div>
            </section>

            <!-- SAIR -->
            <section id="sair" class="section" style="display: none;">
                <div class="logout-container">
                    <i class="fas fa-sign-out-alt"></i>
                    <h2>Sair do Sistema</h2>
                    <p>Tem certeza que deseja sair?</p>
                    <div class="logout-actions">
                        <a href="logout.php" class="btn-primary">Confirmar Saída</a>
                        <button onclick="showSection('dashboard')" class="btn-secondary">Cancelar</button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://jspm.dev/uuid"></script>
    <script src="user.js"></script>
</body>
</html>