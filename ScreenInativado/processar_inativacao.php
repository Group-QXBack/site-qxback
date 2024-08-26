<?php
session_start(); 
if (!isset($_SESSION['usuario_id'])) {
    echo "ID do Usuário não definido na sessão.";
    exit();
}

require '../ScreenCadastro/config.php'; // Caminho para o arquivo de configuração

// Debug: Verifique o motivo da solicitação
$motivo = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';
if (empty($motivo)) {
    echo "Motivo não fornecido.";
    exit();
}

// Obtém o ID do usuário logado
$usuario_id = $_SESSION['usuario_id'];

$stmt = $conexao->prepare("INSERT INTO solicitacoes_inativacao (usuario_id, motivo) VALUES (?, ?)");
$stmt->bind_param("is", $usuario_id, $motivo);

if ($stmt->execute()) {
    echo "Sua solicitação de inativação foi enviada com sucesso.";
} else {
    echo "Erro ao enviar a solicitação: " . $stmt->error;
}
$stmt->close();
$conexao->close();
?>
