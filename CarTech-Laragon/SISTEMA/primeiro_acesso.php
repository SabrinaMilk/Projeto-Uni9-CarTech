<?php
// SISTEMA/primeiro_acesso.php
session_start();

// Verificar se está logado e se é primeiro acesso
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'empresa' || $_SESSION['usuario']['logado'] !== true) {
    header('Location: ../MARKETING/LOGIN/login.php');
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
    
    // Buscar dados do usuário
    $stmt = $pdo->prepare("SELECT * FROM usuarios_empresa WHERE id = ? AND primeiro_acesso = TRUE");
    $stmt->execute([$_SESSION['usuario']['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        // Se não for primeiro acesso, redireciona para o sistema
        header('Location: sistema-usuario/usuario.php');
        exit;
    }
    
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Processar alteração de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if (empty($nova_senha) || empty($confirmar_senha)) {
        $erro = "Preencha todos os campos";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem";
    } elseif (strlen($nova_senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres";
    } else {
        try {
            // Atualizar senha e marcar que não é mais primeiro acesso
            $stmt = $pdo->prepare("UPDATE usuarios_empresa SET senha = ?, senha_temporaria = '', primeiro_acesso = FALSE WHERE id = ?");
            $stmt->execute([password_hash($nova_senha, PASSWORD_DEFAULT), $_SESSION['usuario']['id']]);
            
            $_SESSION['sucesso'] = "Senha alterada com sucesso! Bem-vindo ao sistema.";
            
            // REDIRECIONAMENTO CORRETO
            header('Location: sistema-usuario/usuario.php');
            exit;
            
        } catch (PDOException $e) {
            $erro = "Erro ao alterar senha: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primeiro Acesso - CarTech</title>
    <link rel="stylesheet" href="../MARKETING/LOGIN/login.css?v=2.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <!-- Botão Voltar -->
        <div class="back-to-home">
            <a href="../MARKETING/LOGIN/login.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Voltar para Login
            </a>
        </div>

        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-shield-alt"></i>
                <h1>CarTech</h1>
                <p class="login-subtitle">Primeiro Acesso</p>
            </div>
            
            <div class="welcome-message" style="text-align: center; margin-bottom: 25px;">
                <h3 style="color: var(--cor-texto-principal); margin-bottom: 10px;">
                    Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?>!
                </h3>
                <p style="color: var(--cor-texto-secundario); font-size: 14px;">
                    É seu primeiro acesso. Por segurança, altere sua senha temporária.
                </p>
            </div>
            
            <?php if (isset($erro)): ?>
            <div class="error-message show">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($erro); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form active">
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="nova_senha" name="nova_senha" class="form-input" 
                               placeholder="Digite sua nova senha" required minlength="6"
                               oninput="checkPasswordStrength(this.value)">
                        <button type="button" class="password-toggle" onclick="togglePassword('nova_senha')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="password-strength" class="password-strength" style="margin-top: 8px; font-size: 12px; color: var(--cor-texto-secundario);"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Nova Senha</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-input" 
                               placeholder="Confirme sua nova senha" required minlength="6">
                        <button type="button" class="password-toggle" onclick="togglePassword('confirmar_senha')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-save"></i> Alterar Senha e Acessar
                </button>
            </form>

            <!-- Dicas de Segurança -->
            <div class="credential-hint" style="margin-top: 25px;">
                <h4 style="display: flex; align-items: center; gap: 10px; color: var(--cor-destaque); margin-bottom: 15px;">
                    <i class="fas fa-lightbulb"></i> Dicas para uma senha segura
                </h4>
                <div style="font-size: 13px; color: var(--cor-texto-secundario); line-height: 1.5;">
                    • Use pelo menos 6 caracteres<br>
                    • Combine letras, números e símbolos<br>
                    • Evite sequências óbvias<br>
                    • Não use informações pessoais
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = input.parentNode.querySelector('.password-toggle');
            const icon = toggle.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        function checkPasswordStrength(password) {
            const strengthElement = document.getElementById('password-strength');
            let strength = '';
            let className = '';
            
            if (password.length === 0) {
                strength = '';
                strengthElement.style.color = 'var(--cor-texto-secundario)';
            } else if (password.length < 6) {
                strength = 'Senha fraca (mínimo 6 caracteres)';
                strengthElement.style.color = '#f87171';
            } else if (password.length < 8) {
                strength = 'Senha média';
                strengthElement.style.color = '#fbbf24';
            } else {
                strength = 'Senha forte';
                strengthElement.style.color = '#4ade80';
            }
            
            strengthElement.textContent = strength;
        }

        // Validação do formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            const novaSenha = document.getElementById('nova_senha').value;
            const confirmarSenha = document.getElementById('confirmar_senha').value;
            
            if (novaSenha.length < 6) {
                e.preventDefault();
                alert('A senha deve ter pelo menos 6 caracteres.');
                return;
            }
            
            if (novaSenha !== confirmarSenha) {
                e.preventDefault();
                alert('As senhas não coincidem.');
                return;
            }
        });
    </script>
</body>
</html>