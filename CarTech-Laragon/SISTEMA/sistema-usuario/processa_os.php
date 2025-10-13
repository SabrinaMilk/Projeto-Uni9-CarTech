<?php
session_start();

// Simular dados - na prática você usaria MySQL
if (!isset($_SESSION['ordens_servico'])) {
    $_SESSION['ordens_servico'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gerar ID único e número da OS
    $os_id = uniqid('os_');
    $numero_os = 'OS' . date('YmdHis') . rand(100, 999);
    
    // Processar previsão de reparo
    $previsao_reparo = $_POST['previsao_reparo'];
    if ($previsao_reparo === 'data_especifica' && !empty($_POST['data_especifica'])) {
        $previsao_reparo = $_POST['data_especifica'];
    }
    
    $nova_os = [
        'id' => $os_id,
        'numero_os' => $numero_os,
        'data_cadastro' => date('Y-m-d H:i:s'),
        'placa' => strtoupper($_POST['placa']),
        'marca' => $_POST['marca'],
        'modelo' => $_POST['modelo'],
        'ano' => $_POST['ano'],
        'cor' => $_POST['cor'],
        'nome_cliente' => $_POST['nome_cliente'],
        'cpf_cliente' => $_POST['cpf_cliente'],
        'telefone_cliente' => $_POST['telefone_cliente'],
        'email_cliente' => $_POST['email_cliente'] ?? '',
        'cep' => $_POST['cep'] ?? '',
        'rua' => $_POST['rua'] ?? '',
        'numero_endereco' => $_POST['numero_endereco'] ?? '',
        'bairro' => $_POST['bairro'] ?? '',
        'cidade' => $_POST['cidade'] ?? '',
        'relato_cliente' => $_POST['relato_cliente'],
        'status' => $_POST['status'],
        'previsao_reparo' => $previsao_reparo,
        'diagnostico' => '',
        'observacoes' => '',
        'valor_orcamento' => 0
    ];
    
    // Adicionar à sessão
    array_unshift($_SESSION['ordens_servico'], $nova_os);
    
    // Retornar sucesso
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'OS cadastrada com sucesso!',
        'os' => $nova_os
    ]);
    exit;
    
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
    exit;
}
?>