<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenUser/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$servicos_id = filter_input(INPUT_GET, 'servicos_id', FILTER_VALIDATE_INT);
$indicacao_id = filter_input(INPUT_GET, 'indicacao_id', FILTER_VALIDATE_INT);
$status_filter = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

if (!$action || !$servicos_id || !$indicacao_id) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Ação, ID do serviço ou ID da indicação inválidos.'
    ];
    header("Location: indicacoes.php?status=" . urlencode($status_filter));
    exit();
}

$allowed_actions = ['aceitar', 'negar'];
if (!in_array($action, $allowed_actions)) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Ação inválida.'
    ];
    header("Location: indicacoes.php?status=" . urlencode($status_filter));
    exit();
}

$status = ($action === 'aceitar') ? 'Aceita' : 'Negada';
$usuario_id = $_SESSION['usuario']['id']; 

$sql = "UPDATE indicacoes_servicos 
        SET status = ?, ultima_atualizacao = NOW() 
        WHERE servicos_id = ? AND indicacao_id = ?";
$stmt = $conexao->prepare($sql);

if ($stmt) {
    $stmt->bind_param('sii', $status, $servicos_id, $indicacao_id);
    
    if ($stmt->execute()) {
        if ($action === 'aceitar') {
            $sql_update_saldo = "UPDATE usuarios u 
                                 JOIN indicacoes i ON u.id = i.usuario_id 
                                 SET u.saldo = 10 
                                 WHERE i.id = (SELECT indicacao_id FROM indicacoes_servicos WHERE indicacao_id = ?)";
            $stmt_update_saldo = $conexao->prepare($sql_update_saldo);
            $stmt_update_saldo->bind_param('i', $indicacao_id);
            $stmt_update_saldo->execute();
            $stmt_update_saldo->close();
        }

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => "Serviço foi $status com sucesso!"
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Erro ao atualizar o status do serviço.'
        ];
    }

    $stmt->close();
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro na preparação da consulta.'
    ];
}

$conexao->close();

header("Location: indicacoes.php?status=" . urlencode($status_filter));
exit();
?>
