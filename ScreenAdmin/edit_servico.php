<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenLogin/index.html");
    exit();
}
include '../ScreenCadastro/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $servico_id = $_GET['id'];

    // Buscar informações do serviço
    $sql = "SELECT * FROM servicos WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $servico_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $servico = $result->fetch_assoc();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Serviço não encontrado.'];
        header("Location: cadastros.php");
        exit();
    }
    $stmt->close();

    // Buscar empresas disponíveis
    $sql_empresas = "SELECT id, nome_empresa FROM empresas";
    $empresas_result = $conexao->query($sql_empresas);

    // Buscar empresa responsável pelo serviço
    $sql_responsavel = "
        SELECT empresa_id FROM empresa_servico WHERE servico_id = ?";
    $stmt_responsavel = $conexao->prepare($sql_responsavel);
    $stmt_responsavel->bind_param("i", $servico_id);
    $stmt_responsavel->execute();
    $responsavel_result = $stmt_responsavel->get_result();
    $responsavel = $responsavel_result->fetch_assoc();
    $empresa_responsavel_id = $responsavel ? $responsavel['empresa_id'] : '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome_servico = $_POST['nome_servico'];
        $descricao = $_POST['descricao'];
        $empresa_responsavel_id = $_POST['empresa_responsavel'];

        // Atualizar dados do serviço
        $sql = "UPDATE servicos SET nome = ?, descricao = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssi", $nome_servico, $descricao, $servico_id);

        if ($stmt->execute()) {
            // Atualizar empresa responsável
            $sql_update_responsavel = "INSERT INTO empresa_servico (servico_id, empresa_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE empresa_id = VALUES(empresa_id)";
            $stmt_update_responsavel = $conexao->prepare($sql_update_responsavel);
            $stmt_update_responsavel->bind_param("ii", $servico_id, $empresa_responsavel_id);
            $stmt_update_responsavel->execute();

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Serviço atualizado com sucesso!'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro ao atualizar serviço.'];
        }
        $stmt->close();
        $stmt_update_responsavel->close();

        header("Location: cadastros.php");
        exit();
    }
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ID do serviço inválido.'];
    header("Location: cadastros.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
            color: whitesmoke;
            text-align: center;
            background-size: cover; 
            background-attachment: fixed; 
            margin: 0; 
            padding: 0; 
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
        input[type="text"], textarea {
            padding: 8px;
            border: none;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: rgb(75, 198, 133);
        }
        .success-message, .error-message {
            padding: 10px;
            border-radius: 4px;
            margin: 10px auto;
            width: 80%;
            max-width: 600px;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
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
        
        fieldset{
            border: 1px solid rgb(113, 202, 150);
            border-radius: 4px;
        }
        legend{
            border: 1px solid rgb(113, 202, 150);
            padding: 5px;
            width: 250px;
            text-align: center;
            background-color: rgb(113, 202, 150);
            border-radius: 4px;
        }
        .box{
            color: aliceblue;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.4);
            padding: 10px;
            border-radius: 4px;
            width: 555px;
            font-size: 14px;
            text-align: center;
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
    <h1>Editar Serviço</h1>
    <div class="box">
        <form method="POST" action="">
            <fieldset>
                <legend>Editar Serviço</legend>
                <label for="nome_servico">Nome do Serviço:</label>
                <input type="text" id="nome_servico" name="nome_servico" value="<?php echo htmlspecialchars($servico['nome']); ?>" required><br><br>
                
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4" cols="50" required><?php echo htmlspecialchars($servico['descricao']); ?></textarea><br><br>
                
                <label for="empresa_responsavel">Empresa Responsável:</label>
                <select id="empresa_responsavel" name="empresa_responsavel">
                    <option value="">Selecione uma empresa</option>
                    <?php while ($empresa = $empresas_result->fetch_assoc()): ?>
                        <option value="<?php echo $empresa['id']; ?>" <?php echo ($empresa['id'] == $empresa_responsavel_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($empresa['nome_empresa']); ?>
                        </option>
                    <?php endwhile; ?>
                </select><br><br>
                
                <input type="submit" value="Atualizar Serviço">
            </fieldset>
        </form>
        <div class="bottom-buttons">
            <a href="cadastros.php?tipo_cadastro=servicos">Voltar</a>
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