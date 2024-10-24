<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenLogin/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_servico = trim($_POST['nome_servico']);
    $descricao = trim($_POST['descricao']);
    $empresa_id = intval($_POST['empresa_id']);

    $sql = "SELECT id FROM servicos WHERE nome = ? AND descricao = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $nome_servico, $descricao);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Serviço já cadastrado.'];
        $stmt->close();
        header("Location: cadastro_servico.php");
        exit();
    }
    $stmt->close();

    $sql = "INSERT INTO servicos (nome, descricao) VALUES (?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $nome_servico, $descricao);

    if ($stmt->execute()) {
        $servico_id = $stmt->insert_id;
        $stmt->close();

        $sql = "INSERT INTO empresa_servico (empresa_id, servico_id) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $empresa_id, $servico_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Serviço cadastrado e associado com sucesso!'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro ao associar o serviço com a empresa.'];
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro ao cadastrar serviço.'];
    }

    header("Location: cadastro_servico.php");
    exit();
}
$sql = "SELECT id, nome_empresa FROM empresas";
$empresas_result = $conexao->query($sql);

if (!$empresas_result) {
    echo "Erro ao recuperar empresas: " . $conexao->error;
    $conexao->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Serviços</title>
    <style>
            body {
                font-family: 'Red Hat Display', Arial, sans-serif;
                background-color: #161616;
                color: whitesmoke;
                margin: 0;
                padding: 0;
                text-align: center;
            }

            header {
                width: 100%;
                height: 75px;
                background-color: #1d1d1d;
                display: flex;
                justify-content: center;
                align-items: center;
                border-bottom: 2px solid #42FF00;
            }

            .logo {
                max-width: 200px;
                height: auto;
            }
            .section-buttons {
                justify-content: center;
                margin-bottom: 20px;
            }
            .section-buttons button {
                padding: 8px;
                border: none;
                border-radius: 4px;
                background-color: rgb(0, 0, 0, 0.3);
                color: whitesmoke;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .section-buttons button:hover {
                background-color: rgb(75, 198, 133);
            }
            .form-container {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .form-container label {
                margin-right: 10px;
            }
            input[type="text"], input[type="textarea"], select {
                padding: 8px;
                border: none;
                border-radius: 4px;
                margin-bottom: 10px;
            }
            input[type="submit"] {
                    border: none;
                    padding: 10px 20px;
                    background-color: rgba(0, 0, 0, 0.3);
                    color: #fff;
                    border-radius: 5px;
                    transition: background-color 0.3s;
                    font-size: 16px;
                    text-decoration: none;
                    display: inline-block;
            }
            input[type="submit"]:hover {
                background-color: rgb(75, 198, 133);
            }
            .bottom-buttons a {
                padding: 10px 20px;
                background-color: rgba(0, 0, 0, 0.3);
                color: #fff;
                border-radius: 5px;
                transition: background-color 0.3s;
                font-size: 16px;
                text-decoration: none;
                display: inline-block;
                margin-top: 10px;
            }
            .bottom-buttons a:hover {
                background-color: rgb(75, 198, 133);
            }
            label{
                color: #000;
            }
            
            fieldset{
                border: 1px solid #42FF00;
                border-radius: 4px;
                }
            legend{
                color: #000;
                font-weight: 600;
                border: 1px solid #42FF00;
                padding: 5px;
                width: 250px;
                text-align: center;
                background-color: #42FF00;
                border-radius: 4px;
            }
            .box{
                color: aliceblue;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: #D9D9D9;
                padding: 10px;
                border-radius: 4px;
                width: 555px;
                font-size: 14px;
                text-align: center;
            }
    </style>
</head>
<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="Logo da página">
    </header>
    <div class="box">
        <form method="POST" action="cadastro_servico.php">
            <fieldset>
                <legend>Cadastrar Serviços</legend>
            <label for="nome_servico">Nome do Serviço:</label>
            <input type="text" id="nome_servico" name="nome_servico" required><br><br>
            
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4" cols="50" required></textarea><br><br>
            
            <label for="empresa_id">Empresa:</label>
            <select id="empresa_id" name="empresa_id" required>
                <?php while ($row = $empresas_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome_empresa']); ?></option>
                <?php endwhile; ?>
            </select><br><br>
            
            <input type="submit" value="Cadastrar Serviço">
        </form>
    <div class="bottom-buttons">
    <a href="cadastros.php?tipo_cadastro=empresas">Voltar</a>
        </fieldset>
    </div>
    </div>
    

    <?php if (isset($_SESSION['message'])): ?>
        <div class="<?php echo $_SESSION['message']['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
            <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</body>
</html>
