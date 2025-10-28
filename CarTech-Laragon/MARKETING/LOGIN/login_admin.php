<?php
// login_admin.php
session_start();

// Credenciais do ADM
$adm_email = "cartech.mecanicas@gmail.com";
$adm_senha = "uni9TI2025";

// Processar login administrador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Login ADM (credenciais fixas)
    if ($email === $adm_email && $senha === $adm_senha) {
        $_SESSION['usuario'] = [
            'email' => $email,
            'tipo' => 'adm',
            'nome' => 'Administrador CarTech',
            'logado' => true
        ];
        header('Location: ../../SISTEMA/sistema-adm/admin_dashboard.php');
        exit;
    } else {
        $erro = "Credenciais inválidas para administrador";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador - CarTech</title>
    <link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="login.css">    
<link rel="stylesheet" href="login.css?v=2.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <!-- Botão Voltar -->
        <div class="back-to-home">
            <a href="/MARKETING/marketing.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Voltar para o Site
            </a>
        </div>

        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-crown"></i>
                <h1>CarTech</h1>
                <p class="login-subtitle">Área Administrativa</p>
            </div>
            
            <?php if (isset($erro)): ?>
            <div class="error-message show">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($erro); ?>
            </div>
            <?php endif; ?>
            
            <form class="login-form active" method="POST">
                <div class="form-group">
                    <label for="email">E-mail do Administrador</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="cartech.mecanicas@gmail.com" required autocomplete="off">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha do Administrador</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="senha" name="senha" class="form-input" 
                               placeholder="uni9TI2025" required autocomplete="off">
                        <button type="button" class="password-toggle" onclick="togglePassword('senha')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-crown"></i> Entrar como Administrador
                </button>
            </form>
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

        // Auto-preenchimento para demonstração
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const senhaInput = document.getElementById('senha');
            
            if (emailInput && !emailInput.value) {
                emailInput.value = 'cartech.mecanicas@gmail.com';
            }
            if (senhaInput && !senhaInput.value) {
                senhaInput.value = 'uni9TI2025';
            }
        });
    </script>
</body>
</html>