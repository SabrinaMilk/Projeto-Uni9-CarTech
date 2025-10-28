<?php
// MARKETING/LOGIN/login_empresa.php
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

// Verificar mensagens de sucesso da sessão
$sucesso = '';
if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}

// Processar login empresa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
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
                    
                    // VERIFICAR SE É PRIMEIRO ACESSO - CORREÇÃO DOS CAMINHOS
                    if ($usuario['primeiro_acesso']) {
                        header('Location: ../../SISTEMA/primeiro_acesso.php');
                    } else {
                        header('Location: ../../SISTEMA/sistema-usuario/usuario.php');
                    }
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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Empresa - CarTech</title>
    <link rel="stylesheet" href="login.css?v=2.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Modal de Sucesso */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal-success {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(31, 41, 55, 0.95);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 15px;
            padding: 30px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 1001;
            backdrop-filter: blur(20px);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #86efac;
            font-size: 18px;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--cor-texto-secundario);
            font-size: 20px;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
            transition: var(--transicao-rapida);
        }

        .modal-close:hover {
            color: var(--cor-destaque);
            background: rgba(255, 255, 255, 0.1);
        }

        .modal-content {
            color: var(--cor-texto-principal);
            line-height: 1.5;
            text-align: center;
        }

        .modal-icon {
            font-size: 3rem;
            color: #86efac;
            margin-bottom: 15px;
        }

        .modal-actions {
            margin-top: 25px;
            display: flex;
            justify-content: center;
        }

        .btn-continue {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.8), rgba(21, 128, 61, 0.9));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: var(--raio-borda);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transicao-rapida);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-continue:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Botão Voltar -->
        <div class="back-to-home">
            <a href="../marketing.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Voltar para o Site
            </a>
        </div>

        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-building"></i>
                <h1>CarTech</h1>
                <p class="login-subtitle">Área da Empresa</p>
            </div>
            
            <?php if (isset($erro)): ?>
            <div class="error-message show">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($erro); ?>
            </div>
            <?php endif; ?>
            
            <form class="login-form active" method="POST">
                <div class="form-group">
                    <label for="email">E-mail da Empresa</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="empresa@exemplo.com" required autocomplete="off">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="senha" name="senha" class="form-input" 
                               placeholder="Sua senha" required autocomplete="off">
                        <button type="button" class="password-toggle" onclick="togglePassword('senha')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Link Esqueci Senha -->
                <div style="text-align: center; margin-top: 20px;">
                    <a href="esqueci_senha.php" style="color: var(--cor-texto-secundario); text-decoration: none; font-size: 14px; transition: var(--transicao-rapida);">
                        <i class="fas fa-question-circle"></i> Esqueci minha senha
                    </a>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Entrar na Empresa
                </button>
            </form> 
        </div>
    </div>

    <!-- Modal de Sucesso -->
    <?php if (!empty($sucesso)): ?>
    <div class="modal-overlay" id="successModal" style="display: block;">
        <div class="modal-success">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-check-circle"></i>
                    Sucesso!
                </div>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="modal-icon">
                    <i class="fas fa-shield-check"></i>
                </div>
                <p><?php echo htmlspecialchars($sucesso); ?></p>
            </div>
            <div class="modal-actions">
                <button class="btn-continue" onclick="closeModal()">
                    <i class="fas fa-check"></i>
                    Continuar
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

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

        // Funções do Modal
        function closeModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Fechar modal clicando fora
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('successModal');
            if (modal && e.target === modal) {
                closeModal();
            }
        });

        // Fechar modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Auto-fechar após 5 segundos (opcional)
        <?php if (!empty($sucesso)): ?>
        setTimeout(function() {
            closeModal();
        }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>