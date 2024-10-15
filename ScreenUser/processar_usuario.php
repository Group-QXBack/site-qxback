<?php
session_start(); 

include '../ScreenCadastro/config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);

    if (!empty($nome) && !empty($sobrenome)) {
         $usuario = $_SESSION['usuario']; 
        $userId = $usuario['id']; 

        if (!is_numeric($userId)) {
            echo "ID de usuário inválido.";
            exit();
        }

        $stmt = $conexao->prepare("UPDATE usuarios SET nome = ?, sobrenome = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $sobrenome, $userId);
        if ($stmt->execute()) {
            header("Location: index.php?msg=Atualização bem-sucedida");
            exit();
        } else {
            echo "Erro ao atualizar os dados: " . $stmt->error;
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

$conexao->close();
?>
