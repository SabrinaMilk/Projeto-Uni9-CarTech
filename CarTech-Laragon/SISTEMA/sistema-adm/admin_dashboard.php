<?php
session_start();

// Verificar se está logado como ADM
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'adm' || $_SESSION['usuario']['logado'] !== true) {
    header('Location: ../../LOGIN/login.php');
    exit;
}

// Conexão com banco de dados
$host = 'localhost';
$dbname = 'sistema_cartech';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // VERIFICAR SE A TABELA EXISTE
    $tabelaExiste = $pdo->query("SHOW TABLES LIKE 'usuarios_empresa'")->rowCount() > 0;
    
    if (!$tabelaExiste) {
        // Criar tabela completa
        $criarTabela = "
        CREATE TABLE usuarios_empresa (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nome_empresa VARCHAR(255) NOT NULL,
            nome_proprietario VARCHAR(255) NOT NULL,
            tipo_documento ENUM('CPF', 'CNPJ') NOT NULL,
            documento VARCHAR(20) NOT NULL UNIQUE,
            cep VARCHAR(10) NOT NULL,
            rua VARCHAR(255) NOT NULL,
            numero VARCHAR(10) NOT NULL,
            bairro VARCHAR(100) NOT NULL,
            cidade VARCHAR(100) NOT NULL,
            estado VARCHAR(2) NOT NULL,
            complemento VARCHAR(255),
            telefone VARCHAR(20) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            quantidade_usuarios INT DEFAULT 1,
            status ENUM('ativo', 'inativo') DEFAULT 'ativo',
            observacoes TEXT,
            data_cadastro DATETIME NOT NULL
        )";
        $pdo->exec($criarTabela);
    } else {
        // VERIFICAR E ADICIONAR COLUNAS FALTANTES
        $colunas = $pdo->query("SHOW COLUMNS FROM usuarios_empresa")->fetchAll(PDO::FETCH_COLUMN);
        
        if (!in_array('observacoes', $colunas)) {
            $pdo->exec("ALTER TABLE usuarios_empresa ADD COLUMN observacoes TEXT AFTER status");
        }
    }
    
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

/// Processar criação de usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['criar_usuario'])) {
        $dados = $_POST;
        
        // Gerar senha aleatória
        $senha_aleatoria = gerarSenhaAleatoria();
        
        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios_empresa 
                (nome_empresa, nome_proprietario, tipo_documento, documento, 
                 cep, rua, numero, bairro, cidade, estado, complemento,
                 telefone, email, senha, data_cadastro, status, quantidade_usuarios, observacoes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'ativo', ?, ?)");
            
            $stmt->execute([
                $dados['nome_empresa'],
                $dados['nome_proprietario'],
                $dados['tipo_documento'],
                $dados['documento'],
                $dados['cep'],
                $dados['rua'],
                $dados['numero'],
                $dados['bairro'],
                $dados['cidade'],
                $dados['estado'],
                $dados['complemento'],
                $dados['telefone'],
                $dados['email'],
                password_hash($senha_aleatoria, PASSWORD_DEFAULT),
                $dados['quantidade_usuarios'],
                $dados['observacoes'] ?? ''
            ]);
            
            // Gerar link do WhatsApp
            $url_whatsapp = enviarMensagemWhatsApp(
                $dados['telefone'],
                $dados['nome_empresa'],
                $dados['email'],
                $senha_aleatoria
            );
            
            $_SESSION['sucesso'] = "Usuário criado com sucesso! Senha: " . $senha_aleatoria . 
                                  " | <a href='" . $url_whatsapp . "' target='_blank' style='color: #10b981; text-decoration: underline;'>📱 Enviar credenciais via WhatsApp</a>";
            
            // MANTER NA MESMA ABA
            header('Location: admin_dashboard.php?tab=criar-usuario');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao criar usuário: " . $e->getMessage();
            header('Location: admin_dashboard.php?tab=criar-usuario');
            exit;
        }
    }
    
    // Processar atualização de status
    if (isset($_POST['alterar_status'])) {
        $usuario_id = $_POST['usuario_id'];
        $novo_status = $_POST['novo_status'];
        
        try {
            $stmt = $pdo->prepare("UPDATE usuarios_empresa SET status = ? WHERE id = ?");
            $stmt->execute([$novo_status, $usuario_id]);
            
            $_SESSION['sucesso'] = "Status do usuário atualizado com sucesso!";
            // MANTER NA LISTA DE USUÁRIOS
            header('Location: admin_dashboard.php?tab=lista-usuarios');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar status: " . $e->getMessage();
            header('Location: admin_dashboard.php?tab=lista-usuarios');
            exit;
        }
    }
    
    // Processar exclusão de usuário
    if (isset($_POST['excluir_usuario'])) {
        $usuario_id = $_POST['usuario_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM usuarios_empresa WHERE id = ?");
            $stmt->execute([$usuario_id]);
            
            $_SESSION['sucesso'] = "Usuário excluído com sucesso!";
            // MANTER NA LISTA DE USUÁRIOS
            header('Location: admin_dashboard.php?tab=lista-usuarios');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao excluir usuário: " . $e->getMessage();
            header('Location: admin_dashboard.php?tab=lista-usuarios');
            exit;
        }
    }
    
    // Processar atualização de observações
    if (isset($_POST['salvar_observacoes'])) {
        $usuario_id = $_POST['usuario_id'];
        $observacoes = $_POST['observacoes'];
        
        try {
            $stmt = $pdo->prepare("UPDATE usuarios_empresa SET observacoes = ? WHERE id = ?");
            $stmt->execute([$observacoes, $usuario_id]);
            
            $_SESSION['sucesso'] = "Observações salvas com sucesso!";
            // MANTER NA LISTA DE USUÁRIOS
            header('Location: admin_dashboard.php?tab=lista-usuarios');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao salvar observações: " . $e->getMessage();
            header('Location: admin_dashboard.php?tab=lista-usuarios');
            exit;
        }
    }
    
    // Processar edição de usuário
    if (isset($_POST['editar_usuario'])) {
        $usuario_id = $_POST['usuario_id'];
        $dados = $_POST;
        
        try {
            $stmt = $pdo->prepare("UPDATE usuarios_empresa SET 
                nome_empresa = ?, nome_proprietario = ?, tipo_documento = ?, documento = ?,
                cep = ?, rua = ?, numero = ?, bairro = ?, cidade = ?, estado = ?, complemento = ?,
                telefone = ?, email = ?, quantidade_usuarios = ?, observacoes = ?
                WHERE id = ?");
            
            $stmt->execute([
                $dados['nome_empresa'],
                $dados['nome_proprietario'],
                $dados['tipo_documento'],
                $dados['documento'],
                $dados['cep'],
                $dados['rua'],
                $dados['numero'],
                $dados['bairro'],
                $dados['cidade'],
                $dados['estado'],
                $dados['complemento'],
                $dados['telefone'],
                $dados['email'],
                $dados['quantidade_usuarios'],
                $dados['observacoes'] ?? '',
                $usuario_id
            ]);
            
            $_SESSION['sucesso'] = "Usuário atualizado com sucesso!";
            // MANTER NA LISTA DE USUÁRIOS
            header('Location: admin_dashboard.php?tab=lista-usuarios');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar usuário: " . $e->getMessage();
            header('Location: admin_dashboard.php?tab=lista-usuarios');
            exit;
        }
    }
}

// Determinar aba ativa
$aba_ativa = $_GET['tab'] ?? 'criar-usuario';

// Buscar usuários para a lista
$search = $_GET['search'] ?? '';
$usuarios = [];

try {
    if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios_empresa 
                              WHERE nome_empresa LIKE ? OR nome_proprietario LIKE ? OR email LIKE ? OR documento LIKE ?
                              ORDER BY data_cadastro DESC");
        $searchTerm = "%$search%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    } else {
        $stmt = $pdo->query("SELECT * FROM usuarios_empresa ORDER BY data_cadastro DESC");
    }
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar usuários: " . $e->getMessage();
}

function gerarSenhaAleatoria($tamanho = 8) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%';
    $senha = '';
    for ($i = 0; $i < $tamanho; $i++) {
        $senha .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $senha;
}

function enviarMensagemWhatsApp($telefone, $nome_empresa, $email, $senha) {
    // Remove caracteres não numéricos do telefone
    $telefone_limpo = preg_replace('/[^0-9]/', '', $telefone);
    
    // Verifica se tem DDD (adiciona se não tiver)
    if (strlen($telefone_limpo) === 8 || strlen($telefone_limpo) === 9) {
        $telefone_limpo = '55' . $telefone_limpo;
    }
    
    // Mensagem personalizada
    $mensagem = rawurlencode("🚗 *Bem-vindo ao Sistema CarTech!* 🚗

🎉 *Parabéns, sua empresa foi cadastrada com sucesso!*

📋 *Seus dados de acesso:*
🏢 *Empresa:* $nome_empresa
📧 *E-mail:* $email
🔑 *Senha:* $senha

🌐 *Acesse nosso sistema:*
http://cartech-laragon.test/LOGIN/login.php

⚠️ *Importante:*
- Esta é uma senha temporária
- Recomendamos alterar a senha no primeiro acesso
- Mantenha seus dados confidenciais

📞 *Dúvidas?* Entre em contato conosco.

*Atenciosamente,*
*Equipe CarTech*");

    // Cria o link do WhatsApp
    $url_whatsapp = "https://wa.me/{$telefone_limpo}?text={$mensagem}";
    
    return $url_whatsapp;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CarTech</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <i class="fas fa-tools"></i>
                <h2>CarTech</h2>
                <p>Administração</p>
            </div>
            
           <nav class="sidebar-nav">
    <a href="admin_dashboard.php?tab=criar-usuario" class="nav-item <?php echo $aba_ativa === 'criar-usuario' ? 'active' : ''; ?>" data-tab="criar-usuario">
        <i class="fas fa-user-plus"></i>
        <span>Criar Usuário</span>
    </a>
    <a href="admin_dashboard.php?tab=lista-usuarios" class="nav-item <?php echo $aba_ativa === 'lista-usuarios' ? 'active' : ''; ?>" data-tab="lista-usuarios">
        <i class="fas fa-users"></i>
        <span>Lista de Usuários</span>
    </a>
    <a href="../../LOGIN/login.php" class="nav-item logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Sair</span>
    </a>
</nav>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
           <header class="admin-header">
    <h1>Painel Administrativo</h1>
    <div class="user-info">
        <span><strong>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Administrador CarTech'); ?></strong></span>
    </div>
</header>

            <div class="admin-content">
                <!-- Mensagens -->
                <?php if (isset($_SESSION['sucesso'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?>
                    </div>
                <?php endif; ?>

                <!-- Tab: Criar Usuário -->
<div id="criar-usuario" class="tab-content <?php echo $aba_ativa === 'criar-usuario' ? 'active' : ''; ?>">
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-user-plus"></i> Cadastrar Nova Empresa</h2>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="form-criar-usuario">
                                <input type="hidden" name="criar_usuario" value="1">
                                
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-building"></i> Dados da Empresa</h3>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="nome_empresa">Nome da Empresa *</label>
                                            <input type="text" id="nome_empresa" name="nome_empresa" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nome_proprietario">Nome do Proprietário *</label>
                                            <input type="text" id="nome_proprietario" name="nome_proprietario" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="tipo_documento">Tipo de Documento *</label>
                                            <select id="tipo_documento" name="tipo_documento" required>
                                                <option value="">Selecione...</option>
                                                <option value="CPF">CPF</option>
                                                <option value="CNPJ">CNPJ</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="documento">Documento *</label>
                                            <input type="text" id="documento" name="documento" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="quantidade_usuarios">Quantidade de Usuários Permitidos *</label>
                                        <input type="number" id="quantidade_usuarios" name="quantidade_usuarios" min="1" max="50" value="1" required>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-address-book"></i> Contato</h3>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="email">E-mail *</label>
                                            <input type="email" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="telefone">Telefone *</label>
                                            <input type="text" id="telefone" name="telefone" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Endereço</h3>
                                    <div class="form-group">
                                        <label for="cep">CEP *</label>
                                        <input type="text" id="cep" name="cep" required>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="rua">Rua *</label>
                                            <input type="text" id="rua" name="rua" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="numero">Número *</label>
                                            <input type="text" id="numero" name="numero" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="bairro">Bairro *</label>
                                            <input type="text" id="bairro" name="bairro" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="cidade">Cidade *</label>
                                            <input type="text" id="cidade" name="cidade" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="estado">Estado *</label>
                                            <input type="text" id="estado" name="estado" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="complemento">Complemento</label>
                                            <input type="text" id="complemento" name="complemento">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-sticky-note"></i> Observações</h3>
                                    <div class="form-group">
                                        <textarea id="observacoes" name="observacoes" placeholder="Observações iniciais sobre o usuário..." rows="4"></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn-primary btn-large">
                                    <i class="fas fa-save"></i> Criar Usuário
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab: Lista de Usuários -->
                 <div id="lista-usuarios" class="tab-content <?php echo $aba_ativa === 'lista-usuarios' ? 'active' : ''; ?>">
                    <div class="card">
                        <div class="card-header">
                            <h2><i class="fas fa-users"></i> Usuários Cadastrados</h2>
                            <div class="header-actions">
                                <div class="search-box">
                                    <form method="GET" class="search-form">
                                        <div class="search-input-wrapper">
                                            <i class="fas fa-search search-icon"></i>
                                            <input type="text" name="search" placeholder="Buscar por empresa, proprietário, email..." 
                                                   value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                                            <?php if (!empty($search)): ?>
                                                <a href="admin_dashboard.php" class="clear-search" title="Limpar busca">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                                <span class="badge"><?php echo count($usuarios); ?> usuários</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($usuarios)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <h3>Nenhum usuário encontrado</h3>
                                    <p><?php echo !empty($search) ? 'Tente alterar os termos da busca.' : 'Comece cadastrando o primeiro usuário.'; ?></p>
                                    <?php if (!empty($search)): ?>
                                        <a href="admin_dashboard.php" class="btn-primary">
                                            <i class="fas fa-times"></i> Limpar Busca
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="table-container">
                                    <table class="users-table">
                                        <thead>
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Proprietário</th>
                                                <th>Documento</th>
                                                <th>E-mail</th>
                                                <th>Telefone</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($usuarios as $usuario): ?>
                                            <tr>
                                                <td>
                                                    <div class="empresa-info">
                                                        <strong><?php echo htmlspecialchars($usuario['nome_empresa']); ?></strong>
                                                        <small><?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?></small>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($usuario['nome_proprietario']); ?></td>
                                                <td>
                                                    <div class="documento-info">
                                                        <span class="documento-tipo"><?php echo $usuario['tipo_documento']; ?></span>
                                                        <span class="documento-numero"><?php echo htmlspecialchars($usuario['documento']); ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                                <td><?php echo htmlspecialchars($usuario['telefone']); ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo $usuario['status']; ?>">
                                                        <i class="fas fa-circle"></i>
                                                        <?php echo ucfirst($usuario['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="acoes-grupo">
                                                        <button class="btn-info btn-detalhes" data-user-id="<?php echo $usuario['id']; ?>">
                                                            <i class="fas fa-eye"></i> Detalhes
                                                        </button>
                                                        
                                                        <!-- Botão Editar Status -->
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                                            <input type="hidden" name="novo_status" value="<?php echo $usuario['status'] === 'ativo' ? 'inativo' : 'ativo'; ?>">
                                                            <button type="submit" name="alterar_status" class="<?php echo $usuario['status'] === 'ativo' ? 'btn-warning' : 'btn-success'; ?>">
                                                                <i class="fas <?php echo $usuario['status'] === 'ativo' ? 'fa-pause' : 'fa-play'; ?>"></i>
                                                                <?php echo $usuario['status'] === 'ativo' ? 'Desativar' : 'Ativar'; ?>
                                                            </button>
                                                        </form>
                                                        
                                                        <!-- Botão Excluir -->
                                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.');">
                                                            <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                                            <button type="submit" name="excluir_usuario" class="btn-danger">
                                                                <i class="fas fa-trash"></i> Excluir
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes -->
    <div id="modal-detalhes" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h3><i class="fas fa-user"></i> Detalhes do Usuário</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Conteúdo carregado via AJAX -->
            </div>
        </div>
    </div>

    <!-- Modal WhatsApp -->
    <div id="modal-whatsapp" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fab fa-whatsapp"></i> Enviar Credenciais via WhatsApp</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Você será redirecionado para o WhatsApp. Verifique se o número está correto antes de enviar.
                </div>
                <div id="whatsapp-details">
                    <!-- Detalhes serão preenchidos via JavaScript -->
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-whatsapp" id="confirmar-whatsapp">
                        <i class="fab fa-whatsapp"></i> Abrir WhatsApp
                    </button>
                    <button type="button" class="btn-secondary" id="cancelar-whatsapp">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>