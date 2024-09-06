<?php
session_start(); 

include '../ScreenCadastro/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $conexao->real_escape_string($_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conexao->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
                        if (password_verify($senha, $row['senha'])) {
                $_SESSION['usuario'] = $row;
                if ($row['tipo_conta'] == 'inativo') {
                    header("Location: ../ScreenInativado/aviso.php?motivo=" . urlencode($row['motivo_inativacao']));
                    exit();
                }
                if ($row['tipo_conta'] == 'admin') {
                    header("Location: ../ScreenAdmin/index.php"); 
                } else {
                    header("Location: ../ScreenUser/index.php"); 
                }
                exit();
            } else {
                header("Location: ../ScreenLogin/index.html?error=Senha incorreta.");
                exit();
            }
        } else {
            header("Location: ../ScreenLogin/index.html?error=Email não cadastrado.");
            exit();
        }
    } else {
        echo "Erro na consulta: " . $conexao->error;
        exit();
    }
    

    $conexao->close();
}
?>
