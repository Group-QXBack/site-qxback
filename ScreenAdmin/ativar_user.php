<?php
session_start();
include_once('../ScreenCadastro/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    $sql = "UPDATE usuarios SET tipo_conta = 'user', data_inativacao = NULL, motivo_inativacao = NULL WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    header('Location: cadastros.php');
    exit();
} else {
    header('Location: cadastros.php');
    exit();
}
?>
