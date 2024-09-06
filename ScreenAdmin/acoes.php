<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

// Sanitizar e validar os parâmetros
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$action || !$id) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Ação ou ID inválidos.'
    ];
    header("Location: indicacoes.php");
    exit();
}

$allowed_actions = ['aceitar', 'negar'];
if (!in_array($action, $allowed_actions)) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Ação inválida.'
    ];
    header("Location: indicacoes.php");
    exit();
}

$status = ($action === 'aceitar') ? 'Aceita' : 'Negada';

$sql = "UPDATE indicacoes SET status = ?, ultima_atualizacao = NOW() WHERE id = ?";
$stmt = $conexao->prepare($sql);

if ($stmt) {
    $stmt->bind_param('si', $status, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => "Indicação foi $status com sucesso!"
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Erro ao atualizar o status da indicação.'
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

header("Location: indicacoes.php");
exit();
?>
