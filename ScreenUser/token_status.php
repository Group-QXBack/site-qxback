<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ScreenUser/style.php">
    <title>Status do Token</title>
    <style>
        body {
            background-color: #161616;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }
        .container {
            text-align: center;
            max-width: 400px;
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        img.logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        h1 {
            margin: 10px 0;
            color: #44ff00;
        }
        p {
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .button {
            background-color: chartreuse;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            color: #161616;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #88ff88;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../imagens/logobranca1.png" alt="Logo" class="logo">
        <?php
        $status = $_GET['status'] ?? 'invalid';
        if ($status === 'invalid') {
            echo "<h1>Token Inválido</h1><p>O token que você forneceu não é válido. Por favor, solicite outro e-mail de redefinição de senha.</p>";
        } else if ($status === 'used_or_expired') {
            echo "<h1>Token Já Utilizado ou Expirado</h1><p>Este token já foi utilizado ou expirou. Você pode solicitar um novo e-mail de redefinição de senha.</p>";
        }
        ?>
        
        <form action="../ScreenLogin/index.html" method="POST">
            <input type="hidden" name="action" value="login">
            <button type="submit" class="button">Logar</button>
        </form>
    </div>
</body>
</html>
