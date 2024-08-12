<?php 
session_start();

include '../ScreenCadastro/config.php';

if(!isset($_SESSION['usuario'])) {
    header("location: ../ScreenUser/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_indicador = $conexao->real_escape_string(trim($_POST['nome_empresa']));
    $cpf_indicador = $conexao->real_escape_string(trim($_POST['cpf']));
    $telefone_indicador = $conexao->real_escape_string(trim($_POST['telefone_empresa']));
    $email_indicador = $conexao->real_escape_string(trim($_POST['email_empresa']));

    if (empty($nome_indicador) || empty($cpf_indicador) || empty($telefone_indicador) || empty($email_indicador)) {
        header("Location: ../ScreenUser/indicar.php?error=Todos os campos são obrigatórios!");
        exit();
    }

    $sql = "INSERT INTO indicacoes (nome_indicador, cpf_indicador, telefone_indicador, email_indicador) 
    VALUES ('$nome_indicador', '$cpf_indicador', '$telefone_indicador', '$email_indicador')";

    if ($conexao->query($sql) === TRUE) {
        header("Location: ../ScreenUser/indicar.php?success=Indicação enviada com sucesso!");
    } else {
        echo "Erro ao enviar indicação: " . $conexao->error;
    }

    $conexao->close();
} else {
    header("Location: ../ScreenUser/indicar.php");
}
?>
