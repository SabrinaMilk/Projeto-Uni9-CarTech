<?php
// MARKETING/LOGIN/redefinir_senha.php
session_start();

// Conexão com banco de dados
$host = 'localhost';
$dbname = 'sistema_cartech';
$username = 'root';
$password = '';

$pdo = null;
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Erro conexão BD: " . $e->getMessage());
}

$token = $_GET['token'] ?? '';
$erro = '';
$token_valido = false;
$usuario = null;

// Verificar token
if (!empty($token)) {
    if ($pdo !== null) {
        try {
            // Buscar usuário pelo token na coluna senha_temporaria
            $stmt = $pdo->prepare("SELECT * FROM usuarios_empresa WHERE senha_temporaria LIKE ? AND status = 'ativo'");
            $stmt->execute([$token . '%']);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                // Extrair token e expiração do campo senha_temporaria
                $dados_recuperacao = explode('|', $usuario['senha_temporaria']);
                $token_armazenado = $dados_recuperacao[0] ?? '';
                $expiracao = $dados_recuperacao[1] ?? '';
                
                // Verificar se o token coincide e não expirou
                if ($token_armazenado === $token && $expiracao > date('Y-m-d H:i:s')) {
                    $token_valido = true;
                } else {
                    $erro = "Link expirado ou inválido";
                }
            } else {
                $erro = "Link inválido ou já utilizado";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao validar link";
        }
    } else {
        $erro = "Sistema temporariamente indisponível";
    }
} else {
    $erro = "Link inválido";
}

// Processar redefinição de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token_valido) {
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
            // Atualizar senha e limpar senha_temporaria
            $stmt = $pdo->prepare("UPDATE usuarios_empresa SET senha = ?, senha_temporaria = '', primeiro_acesso = FALSE WHERE id = ?");
            $stmt->execute([password_hash($nova_senha, PASSWORD_DEFAULT), $usuario['id']]);
            
            $_SESSION['sucesso'] = "Senha redefinida com sucesso! Faça login com sua nova senha.";
            header('Location: login_empresa.php');
            exit;
            
        } catch (PDOException $e) {
            $erro = "Erro ao redefinir senha: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha - CarTech</title>
    <link rel="stylesheet" href="login.css?v=2.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="back-to-home">
            <a href="login.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Voltar para Login
            </a>
        </div>

        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-lock"></i>
                <h1>CarTech</h1>
                <p class="login-subtitle">Nova Senha</p>
            </div>
            
            <?php if ($erro && !$token_valido): ?>
            <div class="error-message show">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($erro); ?>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="esqueci_senha.php" class="login-btn" style="text-decoration: none; display: inline-flex;">
                    <i class="fas fa-sync-alt"></i> Solicitar Novo Link
                </a>
            </div>
            
            <?php elseif ($token_valido): ?>
            
            <?php if ($erro): ?>
            <div class="error-message show">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($erro); ?>
            </div>
            <?php endif; ?>
            
            <!-- CORREÇÃO: Adicionado action="" no formulário -->
            <form class="login-form active" method="POST" action="">
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="nova_senha" name="nova_senha" class="form-input" 
                               placeholder="Digite sua nova senha" required minlength="6">
                        <button type="button" class="password-toggle" onclick="togglePassword('nova_senha')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
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
                    <i class="fas fa-save"></i> Redefinir Senha
                </button>
            </form>
            
            <?php endif; ?>
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

        // Validação client-side
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
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
            }
        });
    </script>
</body>
</html>