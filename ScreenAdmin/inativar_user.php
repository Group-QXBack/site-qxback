<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenUser/index.html");
    exit();
}
include_once('../ScreenCadastro/config.php');

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header('Location: cadastros.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $motivo = $_POST['motivo'];
    $dataInativacao = date('Y-m-d'); 

    $sql = "UPDATE usuarios SET tipo_conta = 'inativo', motivo_inativacao = ?, data_inativacao = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssi", $motivo, $dataInativacao, $userId);
    $stmt->execute();

    header('Location: cadastros.php');
    exit();
} else {
    header('Location: cadastros.php');
    exit();
}
?>
