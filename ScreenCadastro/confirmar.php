<?php
include_once('config.php');

$message = '';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $query = "SELECT * FROM usuarios WHERE email = ? AND token = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $updateQuery = "UPDATE usuarios SET email_confirmado = 1 WHERE email = ?";
        $updateStmt = $conexao->prepare($updateQuery);
        $updateStmt->bind_param("s", $email);

        if ($updateStmt->execute()) {
            $message = "E-mail confirmado com sucesso! Sua conta está ativa.";
        } else {
            $message = "Erro ao confirmar e-mail.";
        }
    } else {
        $message = "E-mail ou token inválidos.";
    }
} else {
    $message = "E-mail ou token não fornecido.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap">
    <link rel="stylesheet" href="./style1.css">
    <title>Confirmação de E-mail</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #001100;
        }

        .container {
            width: 80%;
            height: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.212);
            border-radius: 20px;
            padding: 2rem;
            background-color: #44ff00f8;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1rem;
        }

        .form-header h1 {
            margin: 0;
        }

        .form-header h1::after {
            content: '';
            display: block;
            width: 5rem;
            height: 0.3rem;
            background-color: #fff;
            margin: 0 auto;
            border-radius: 10px;
            margin-top: 0.5rem;
        }

        .message {
            font-size: 1rem;
            color: #333;
            margin: 1rem 0;
        }

        .button {
            background-color: #000;
            color: #fff;
            padding: 0.6rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
        }

        .button:hover {
            background-color: #333;
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h1>Confirmação de E-mail</h1>
        </div>
        <div class="message"><?php echo $message; ?></div>
        <a class="button" href="../ScreenLogin/index.html">Voltar para Login</a>
    </div>
</body>
</html>
