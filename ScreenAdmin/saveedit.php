<?php
session_start();
include_once('../ScreenCadastro/config.php');

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] != 'admin') {
    echo "Acesso restrito.";
    exit();
}

if (isset($_POST['update'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $data_nasc = isset($_POST['data_nasc']) ? $_POST['data_nasc'] : '';
    $genero = isset($_POST['genero']) ? $_POST['genero'] : '';
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $bairro = isset($_POST['bairro']) ? $_POST['bairro'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $numero = isset($_POST['numero']) ? $_POST['numero'] : '';
    $complemento = isset($_POST['complemento']) ? $_POST['complemento'] : '';
    $tipo_conta = isset($_POST['tipo_conta']) ? $_POST['tipo_conta'] : '';

    $sqlUpdate = "UPDATE usuarios SET nome=?, cpf=?, email=?, telefone=?, data_nasc=?, genero=?, cidade=?, estado=?, bairro=?, endereco=?, numero=?, complemento=?, tipo_conta=? WHERE id=?";
    
    if ($stmt = $conexao->prepare($sqlUpdate)) {
        $stmt->bind_param("sssssssssssssi", $nome, $cpf, $email, $telefone, $data_nasc, $genero, $cidade, $estado, $bairro, $endereco, $numero, $complemento, $tipo_conta, $id);

        if ($stmt->execute()) {
            echo "Cadastro atualizado com sucesso";
        } else {
            echo "Erro ao atualizar Cadastro: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conexao->error;
    }

    $conexao->close();
}

header('Location: cadastros.php');
exit();
?>
