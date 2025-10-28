<?php
session_start();

// Verificar se está logado como ADM
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'adm' || !isset($_SESSION['usuario']['logado']) || $_SESSION['usuario']['logado'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado - Sessão inválida']);
    exit;
}

// Conexão com banco de dados
$host = 'localhost';
$dbname = 'sistema_cartech';
$username = 'root';
$password = '';

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]);
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int)$_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios_empresa WHERE id = ?");
        $stmt->execute([$userId]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            // Remove a senha por segurança
            unset($usuario['senha']);
            echo json_encode($usuario);
        } else {
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erro ao buscar usuário: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID inválido']);
}
?>