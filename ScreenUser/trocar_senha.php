<?php
session_start();
include '../ScreenCadastro/config.php';

if (!isset($_GET['token'])) {
    die('Token inválido!');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($nova_senha !== $confirma_senha) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } else {
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE reset_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt = $conexao->prepare("UPDATE usuarios SET senha = ?, reset_token = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", password_hash($nova_senha, PASSWORD_DEFAULT), $token);
            $stmt->execute();

            echo "<script>alert('Senha alterada com sucesso!'); window.location.href='../ScreenLogin/index.html</script>";
            exit();
        } else {
            header("Location: token_status.php?status=invalid");
            exit();
        }
    }
}

$stmt = $conexao->prepare("SELECT * FROM usuarios WHERE reset_token = ?");
$stmt->bind_param("s", $_GET['token']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: token_status.php?status=used_or_expired");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ScreenUser/style.php">
    <title>Trocar Senha</title>
    <style>
        body {
            background-color: #161616;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0; 
        }
        header {
            background-color: #1d1d1d;
            padding: 20px;
            text-align: center;
            position: absolute; 
            top: 0;
            width: 100%;
        }
        .primeira_sessao {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 60px;
            width: 90%;
            max-width: 400px;
            background-color: #1d1d1d;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 10px;
            color: #44ff00;
        }
        p {
            margin: 10px 0;
            text-align: center;
        }
        label {
            color: #fff;
            margin-bottom: 5px;
            display: block;
        }
        input[type="password"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #f8f7f8;
            color: #333;
        }
        button {
            background-color: chartreuse;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #aaff7f;
        }
    </style>
</head>
<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="logo">
    </header>
    <section>
        <div class="primeira_sessao">
            <h1>Trocar Senha</h1>
            <p>Estamos aqui para ajudar você a manter sua conta segura.</p>
            <p>Por favor, insira sua nova senha abaixo e confirme.</p>
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" name="nova_senha" required>
                <label for="confirma_senha">Confirme a Senha:</label>
                <input type="password" name="confirma_senha" required>
                <button type="submit">Alterar Senha</button>
            </form>
            <p>Se você não solicitou a troca de senha, por favor, ignore este e-mail.</p>
        </div>
    </section>
</body>
</html>