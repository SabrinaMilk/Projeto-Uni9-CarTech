<?php
// corrigir_tabela.php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'adm') {
    die('Acesso negado');
}

$host = 'localhost';
$dbname = 'sistema_cartech';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Adicionar coluna observacoes se não existir
    $pdo->exec("ALTER TABLE usuarios_empresa ADD COLUMN IF NOT EXISTS observacoes TEXT AFTER status");
    
    echo "✅ Tabela corrigida com sucesso!";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>