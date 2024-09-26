<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenLogin/index.html");
    exit();
}

include_once('../ScreenCadastro/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome_empresa = $_POST['nome_empresa'];
    $cnpj = $_POST['cnpj'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];

    $sql = "UPDATE empresas SET nome_empresa = ?, cnpj = ?, telefone = ?, email = ?, endereco = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssi", $nome_empresa, $cnpj, $telefone, $email, $endereco, $id);

    if ($stmt->execute()) {
        header("Location: cadastros.php?tipo_cadastro=empresas");
        exit();
    } else {
        echo "Erro ao atualizar dados: " . $stmt->error;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM empresas WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($empresa = $result->fetch_assoc()) {
        $nome_empresa = $empresa['nome_empresa'];
        $cnpj = $empresa['cnpj'];
        $telefone = $empresa['telefone'];
        $email = $empresa['email'];
        $endereco = $empresa['endereco'];
    } else {
        echo "Empresa não encontrada.";
        exit();
    }
} else {
    echo "ID da empresa não fornecido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empresa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #4a4a4a;
            color: whitesmoke;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h1 {
            margin: 20px 0;
            font-size: 2em;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 10px;
            background-color: #333;
            color: whitesmoke;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: rgb(75, 198, 133);
        }
        a {
            color: whitesmoke;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: rgb(0, 0, 0, 0.3);
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: rgb(75, 198, 133);
        }
    </style>
</head>
<body>
    <h1>Editar Empresa</h1>
    <form action="edit_empresa.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <label for="nome_empresa">Nome da Empresa:</label>
        <input type="text" id="nome_empresa" name="nome_empresa" value="<?php echo htmlspecialchars($nome_empresa); ?>" required>
        
        <label for="cnpj">CNPJ:</label>
        <input type="text" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($cnpj); ?>" required>
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        
        <label for="endereco">Endereço:</label>
        <textarea id="endereco" name="endereco" required><?php echo htmlspecialchars($endereco); ?></textarea>
        
        <input type="submit" value="Atualizar">
    </form>
    <br>
    <a href="cadastros.php?tipo_cadastro=empresas">Voltar</a>
</body>
</html>
