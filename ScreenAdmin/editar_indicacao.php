<?php
session_start();

date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenUser/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

$message = [];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'ID inválido.'
    ];
    header("Location: indicacoes.php");
    exit();
}

$sql = "
    SELECT i.*, is_servicos.status AS servico_status, is_servicos.ultima_atualizacao
    FROM indicacoes i
    LEFT JOIN indicacoes_servicos is_servicos ON i.id = is_servicos.indicacao_id
    WHERE i.id = ?
";
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
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    if (!$status) {
        $message = [
            'type' => 'error',
            'text' => 'Status da indicação é obrigatório.'
        ];
    } else {
        $sql_update_indicacao = "UPDATE indicacoes_servicos SET status = ?, ultima_atualizacao = NOW() WHERE indicacao_id = ?";
        $stmt_update_indicacao = $conexao->prepare($sql_update_indicacao);
        $stmt_update_indicacao->bind_param('si', $status, $id);

        if ($stmt_update_indicacao->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Status da indicação atualizado com sucesso!'
            ];
            header("Location: indicacoes.php");
            exit();
        } else {
            $message = [
                'type' => 'error',
                'text' => 'Erro ao atualizar o status da indicação.'
            ];
        }
    }
}

$stmt->close();
$conexao->close();
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
            background-color: #4a4a4a;
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

            <label for="data_indicacao">Data da Indicação</label>
            <input type="date" id="data_indicacao" name="data_indicacao" value="<?php echo htmlspecialchars($indicacao['data_indicacao']); ?>" readonly>


            <label for="status">Status da Indicação</label>
            <select id="status" name="status" required>
                <option value="pendente" <?php echo $indicacao['servico_status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                <option value="Aceita" <?php echo $indicacao['servico_status'] === 'Aceita' ? 'selected' : ''; ?>>Aceita</option>
                <option value="Negada" <?php echo $indicacao['servico_status'] === 'Negada' ? 'selected' : ''; ?>>Negada</option>
            </select>

            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>
