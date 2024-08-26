<?php
    session_start();

    $message = $_SESSION['message'] ?? null;
    unset($_SESSION['message']); 

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../ScreenUser/index.html");
        exit();
    }
    include '../ScreenCadastro/config.php';

    $usuario = $_SESSION['usuario'];
    $userId = $usuario['id'];
?>
  <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./style.css">
        <title>Solicitação de Inativação de Conta</title>
    </head>
    <body> 
        <div class="login">
            <div class="left-login">
                <img src="../imagens/qxback.png" alt="qxback">
            </div>
            <div class="right-login">
                <div class="card-login">
                    <h1>Solicitação de Inativação de Conta</h1>
                    <p>Para solicitar a inativação da sua conta, preencha o motivo abaixo:</p>
                    
                    <form action="processar_inativacao.php" method="post">
                        <div class="input-box">
                            <label for="motivo">Motivo da Solicitação:</label>
                            <textarea id="motivo" name="motivo" rows="4" required></textarea>
                        </div>
                        <button type="submit">Enviar Solicitação</button>
                    </form>
                    
                    <a href="../ScreenUser/logout.php" id="btn-sair">
                        <i class="fi fi-br-exit"></i>
                        <p>Sair</p>
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>
