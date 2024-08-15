<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if (empty($action) || empty($id)) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Ação ou ID não fornecidos.'
    ];
    header("Location: indicacoes.php");
    exit();
}

$allowed_actions = ['aceitar', 'negar'];
if (!in_array($action, $allowed_actions) || !filter_var($id, FILTER_VALIDATE_INT)) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Ação ou ID inválidos.'
    ];
    header("Location: indicacoes.php");
    exit();
}

$status = ($action === 'aceitar') ? 'Aceita' : 'Negada';
$sql = "UPDATE indicacoes SET status = ? WHERE id = ?";
$stmt = $conexao->prepare($sql);
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
$conexao->close();

header("Location: indicacoes.php");
exit();
