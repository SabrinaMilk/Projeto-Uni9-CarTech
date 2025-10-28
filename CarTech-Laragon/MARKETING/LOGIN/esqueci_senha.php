<?php
// MARKETING/LOGIN/esqueci_senha.php
session_start();

// Conexão com banco de dados
$host = 'localhost';
$dbname = 'sistema_cartech';
$username = 'root';
$password = '';

$pdo = null;
$sucesso = '';
$erro = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erro = "Erro de conexão com o banco de dados.";
}

// Processar solicitação de recuperação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo !== null) {
    $email = $_POST['email'] ?? '';
    
    if (!empty($email)) {
        try {
            // Verificar se email existe
            $stmt = $pdo->prepare("SELECT * FROM usuarios_empresa WHERE email = ? AND status = 'ativo'");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                // Gerar token único (vamos usar a coluna senha_temporaria)
                $token = bin2hex(random_bytes(50));
                
                // Calcular expiração (1 hora)
                $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Salvar token na coluna senha_temporaria e usar observacoes para a expiração
                // Formato: token|expiracao
                $dados_recuperacao = $token . '|' . $expiracao;
                
                $stmt = $pdo->prepare("UPDATE usuarios_empresa SET senha_temporaria = ? WHERE id = ?");
                $resultado = $stmt->execute([$dados_recuperacao, $usuario['id']]);
                
                if ($resultado) {
                    // Link de recuperação
                    $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $link_recuperacao = $protocolo . "://" . $_SERVER['HTTP_HOST'] .  "/MARKETING/LOGIN/redefinir_senha.php?token=" . $token;
                    
                    $sucesso = "Link de recuperação gerado com sucesso!<br><br>
                               <strong>Link para redefinir senha:</strong><br>
                               <small style='word-break: break-all;'><a href='$link_recuperacao' style='color: #93c5fd;'>$link_recuperacao</a></small>";
                    
                } else {
                    $erro = "Erro ao gerar link de recuperação.";
                }
                
            } else {
                $erro = "Email não encontrado ou conta inativa";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao processar solicitação.";
        }
    } else {
        $erro = "Digite seu email";
    }
} elseif ($pdo === null) {
    $erro = "Sistema temporariamente indisponível.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - CarTech</title>
    <link rel="stylesheet" href="login.css?v=2.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="back-to-home">
            <a href="login_empresa.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Voltar para Login
            </a>
        </div>

        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-key"></i>
                <h1>CarTech</h1>
                <p class="login-subtitle">Recuperar Senha</p>
            </div>
            
            <?php if ($sucesso): ?>
            <div class="error-message show" style="background: rgba(34, 197, 94, 0.1); color: #86efac; border-color: rgba(34, 197, 94, 0.3);">
                <i class="fas fa-check-circle"></i> 
                <div><?php echo $sucesso; ?></div>
            </div>
            <?php endif; ?>
            
            <?php if ($erro): ?>
            <div class="error-message show">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($erro); ?>
            </div>
            <?php endif; ?>
            
            <?php if ($pdo !== null): ?>
            <form class="login-form active" method="POST">
                <div class="form-group">
                    <label for="email">Digite seu email cadastrado</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="empresa@exemplo.com" required autocomplete="off"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-paper-plane"></i> Gerar Link de Recuperação
                </button>
            </form>
            <?php endif; ?>

            <div class="credential-hint">
                <h4><i class="fas fa-info-circle"></i> Como funciona?</h4>
                <p style="font-size: 13px; margin: 0; color: var(--cor-texto-secundario);">
                    Geramos um link seguro para redefinir sua senha. O link expira em 1 hora.
                </p>
            </div>
        </div>
    </div>
</body>
</html>