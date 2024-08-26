<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

// Inicializar a variável $message
$message = [];

// Obter ID da indicação
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'ID inválido.'
    ];
    header("Location: indicacoes.php");
    exit();
}

// Buscar dados da indicação
$sql = "SELECT * FROM indicacoes WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Indicação não encontrada.'
    ];
    header("Location: indicacoes.php");
    exit();
}

$indicacao = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_empresa = filter_input(INPUT_POST, 'nome_empresa', FILTER_SANITIZE_STRING);
    $cnpj = filter_input(INPUT_POST, 'cnpj', FILTER_SANITIZE_STRING);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $data_indicacao = filter_input(INPUT_POST, 'data_indicacao', FILTER_SANITIZE_STRING);
    $ultima_atualizacao = filter_input(INPUT_POST, 'ultima_atualizacao', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    if (!$nome_empresa || !$cnpj || !$cpf || !$data_indicacao || !$status) {
        $message = [
            'type' => 'error',
            'text' => 'Todos os campos são obrigatórios.'
        ];
    } else {
        $sql = "UPDATE indicacoes SET nome_empresa = ?, cnpj = ?, cpf = ?, data_indicacao = ?, status = ?, ultima_atualizacao = NOW() WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('sssssi', $nome_empresa, $cnpj, $cpf, $data_indicacao, $status, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Indicação atualizada com sucesso!'
            ];
            header("Location: indicacoes.php");
            exit();
        } else {
            $message = [
                'type' => 'error',
                'text' => 'Erro ao atualizar a indicação.'
            ];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/icon_uu.webp" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
    <title>Editar Indicação</title>
    <style>
        body {
            font-family: 'Red Hat Display', Arial, sans-serif;
            background: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
            color: whitesmoke;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            margin: 20px auto;
            max-width: 600px;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2em;
            color: #fff;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input, select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            width: 100%;
        }

        button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: rgb(75, 198, 133);
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            color: #fff;
        }

        .success-message {
            background-color: #28a745;
        }

        .error-message {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Indicação</h1>
        <?php if (!empty($message)): ?>
            <div class="<?php echo isset($message['type']) && $message['type'] === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="nome_empresa">Nome da Empresa</label>
            <input type="text" id="nome_empresa" name="nome_empresa" value="<?php echo htmlspecialchars($indicacao['nome_empresa']); ?>" readonly>

            <label for="cnpj">CNPJ</label>
            <input type="text" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($indicacao['cnpj']); ?>" readonly>

            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($indicacao['cpf']); ?>" readonly>

            <label for="data_indicacao">Data da Indicação</label>
            <input type="date" id="data_indicacao" name="data_indicacao" value="<?php echo htmlspecialchars($indicacao['data_indicacao']); ?>" readonly>

            <label for="ultima_atualizacao">Ultima Atualização</label>
            <input type="date" id="ultima_atualizacao" name="ultima_atualizacao" value="<?php echo htmlspecialchars($indicacao['ultima_atualizacao']); ?>" readonly>

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Em Andamento" <?php echo $indicacao['status'] === 'Em Andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                <option value="Aceita" <?php echo $indicacao['status'] === 'Aceita' ? 'selected' : ''; ?>>Aceita</option>
                <option value="Negada" <?php echo $indicacao['status'] === 'Negada' ? 'selected' : ''; ?>>Negada</option>
            </select>

            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conexao->close();
?>
