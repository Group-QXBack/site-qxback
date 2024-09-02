<?php
session_start(); 

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'inativo') {
    header("Location: ../ScreenUser/index.html");
    exit();
}

require '../ScreenCadastro/config.php'; 

$usuario_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$motivo = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';

if (empty($motivo)) {
    echo "Motivo não fornecido.";
    exit();
}

if ($usuario_id <= 0) {
    echo "ID do usuário inválido.";
    exit();
}

$sql = "SELECT id FROM solicitacoes_inativacao WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['message'] = "Você já possui uma solicitação pendente.";
    header("Location: status_solicitacao.php");
    exit();
}

$stmt = $conexao->prepare("INSERT INTO solicitacoes_inativacao (usuario_id, motivo, status, data_solicitacao) VALUES (?, ?, 'Pendente', NOW())");
$stmt->bind_param("is", $usuario_id, $motivo);

if ($stmt->execute()) {
    $_SESSION['message'] = "Sua solicitação de reativação foi enviada com sucesso.";
    header("Location: status_solicitacao.php"); 
} else {
    echo "Erro ao enviar a solicitação: " . $stmt->error;
}

$stmt->close();
$conexao->close();
?>
