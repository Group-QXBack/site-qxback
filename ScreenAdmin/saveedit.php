<?php
include_once('../ScreenCadastro/config.php');

if(isset($_POST['update']))
{
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $genero = $_POST['genero'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $bairro = $_POST['bairro'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $tipo_conta = $_POST['tipo_conta'];


    $sqlUpdate = "UPDATE usuarios SET nome='$nome', cpf='$cpf', email='$email', telefone='$telefone', data_nasc='$data_nasc', genero='$genero', cidade='$cidade', estado='$estado', bairro='$bairro', endereco='$endereco', numero='$numero', complemento='$complemento', tipo_conta='$tipo_conta' WHERE id='$id'"; 

    if ($conexao->query($sqlUpdate) === TRUE) {
        echo "Cadastro atualizado com sucesso";
    } else {
        echo "Erro ao atualizar Cadastro: " . $conexao->error;
    }
    
    $conexao->close();
}

header('Location: cadastros.php');
?>
