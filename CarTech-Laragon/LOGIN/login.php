<?php
// login.php
session_start();

// Conexão com banco de dados - MOVER PARA O INÍCIO
$host = 'localhost';
$dbname = 'sistema_cartech';
$username = 'root';
$password = '';

$pdo = null;
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Se der erro, $pdo continua null mas o ADM ainda funciona
    error_log("Erro conexão BD: " . $e->getMessage());
}

// Credenciais do ADM
$adm_email = "cartech.mecanicas@gmail.com";
$adm_senha = "uni9TI2025";

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    
    if ($tipo === 'adm') {
        // Login ADM (credenciais fixas)
        if ($email === $adm_email && $senha === $adm_senha) {
            $_SESSION['usuario'] = [
                'email' => $email,
                'tipo' => 'adm',
                'nome' => 'Administrador CarTech',
                'logado' => true
            ];
            header('Location: ../SISTEMA/sistema-adm/admin_dashboard.php');
            exit;
        } else {
            $erro = "Credenciais inválidas para administrador";
        }
    } else {
        // Login Empresa (validação no banco de dados)
        if (!empty($email) && !empty($senha)) {
            if ($pdo !== null) {
                try {
                    $stmt = $pdo->prepare("SELECT * FROM usuarios_empresa WHERE email = ? AND status = 'ativo'");
                    $stmt->execute([$email]);
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($usuario && password_verify($senha, $usuario['senha'])) {
                        $_SESSION['usuario'] = [
                            'id' => $usuario['id'],
                            'email' => $usuario['email'],
                            'tipo' => 'empresa',
                            'nome' => $usuario['nome_empresa'],
                            'logado' => true
                        ];
                        header('Location: ../SISTEMA/sistema-usuario/usuario.php');
                        exit;
                    } else {
                        $erro = "E-mail, senha inválidos ou conta inativa";
                    }
                } catch (PDOException $e) {
                    $erro = "Erro ao validar login. Tente novamente.";
                    error_log("Erro login empresa: " . $e->getMessage());
                }
            } else {
                $erro = "Sistema temporariamente indisponível. Entre em contato com o administrador.";
            }
        } else {
            $erro = "Preencha todos os campos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CarTech</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-tools"></i>
                <h1>CarTech</h1>
                <p class="login-subtitle">Sistema de Gestão Mecânica</p>
            </div>
            
            <?php if (isset($erro)): ?>
            <div class="error-message show">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($erro); ?>
            </div>
            <?php endif; ?>
            
            <div class="login-tabs">
                <button type="button" class="login-tab active" data-tab="empresa">
                    <i class="fas fa-user"></i> Empresa
                </button>
                <button type="button" class="login-tab" data-tab="adm">
                    <i class="fas fa-crown"></i> Administrador
                </button>
            </div>
            
            <!-- Formulário Empresa -->
            <form class="login-form active" id="formEmpresa" method="POST">
                <input type="hidden" name="tipo" value="empresa">
                
                <div class="form-group">
                    <label for="emailEmpresa">E-mail</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="emailEmpresa" name="email" class="form-input" 
                               placeholder="seu.email@exemplo.com" required autocomplete="off">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="senhaEmpresa">Senha</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="senhaEmpresa" name="senha" class="form-input" 
                               placeholder="Sua senha" required autocomplete="off">
                        <button type="button" class="password-toggle" onclick="togglePassword('senhaEmpresa')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Entrar na Empresa
                </button>
            </form>
            
            <!-- Formulário Administrador -->
            <form class="login-form" id="formAdm" method="POST">
                <input type="hidden" name="tipo" value="adm">
                
                <div class="form-group">
                    <label for="emailAdm">E-mail do Administrador</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="emailAdm" name="email" class="form-input" 
                               placeholder="cartech.mecanicas@gmail.com" required autocomplete="off">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="senhaAdm">Senha do Administrador</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="senhaAdm" name="senha" class="form-input" 
                               placeholder="uni9TI2025" required autocomplete="off">
                        <button type="button" class="password-toggle" onclick="togglePassword('senhaAdm')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="credential-hint">
                    <h4><i class="fas fa-info-circle"></i> Credenciais do Administrador</h4>
                    <div class="credential-item">
                        <span>E-mail:</span>
                        <span class="credential-value">cartech.mecanicas@gmail.com</span>
                    </div>
                    <div class="credential-item">
                        <span>Senha:</span>
                        <span class="credential-value">uni9TI2025</span>
                    </div>
                </div>
                
                <button type="submit" class="login-btn" style="margin-top: 20px;">
                    <i class="fas fa-crown"></i> Entrar como Administrador
                </button>
            </form>
            
            <div class="login-info">
                <p><i class="fas fa-shield-alt"></i> Sistema seguro • CarTech &copy; 2024</p>
            </div>
        </div>
    </div>

    <script src="login.js"></script>
</body>
</html>