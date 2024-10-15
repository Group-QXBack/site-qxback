<?php
session_start();
include '../ScreenCadastro/config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenUser/index.html");
    exit();
}

$idSolicitacao = $_GET['id'] ?? null;
$dadosBancarios = null;
$valor = null;
$chavePixDados = null;
$usuario = null; 

if ($idSolicitacao) {
    $sql = "SELECT valor, usuario_id FROM solicitações_resgate WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $idSolicitacao);
    $stmt->execute();
    $solicitacao = $stmt->get_result()->fetch_assoc();

    if ($solicitacao) {
        $valor = number_format((float)$solicitacao['valor'], 2, '.', '');
        $usuarioId = (int)$solicitacao['usuario_id'];

        $sql = "SELECT * FROM contas_bancarias WHERE usuario_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $dadosBancarios = $stmt->get_result()->fetch_assoc();

        $sql = "SELECT tipo_chave, chave FROM chaves_pix WHERE usuario_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $chavePixDados = $stmt->get_result()->fetch_assoc();

        $sql = "SELECT nome, sobrenome FROM usuarios WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Solicitação</title>
    <style>
        body {
            background-color: #161616;
            font-family: "Montserrat", sans-serif;
            color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        h2 {
            margin-bottom: 15px;
            color: #42FF00;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        strong {
            color: #fff;
        }
        .valor {
            font-size: 24px;
            color: #f39c12;
            font-weight: bold;
        }
        .erro {
            text-align: center;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <header>
        <h1>Pagar Solicitação</h1>
    </header>
    <div class="container">
        <?php if ($dadosBancarios && $chavePixDados && $usuario): ?>
            <h2>Dados Bancários do Usuário</h2>
            <p><strong>Banco:</strong> <?php echo htmlspecialchars($dadosBancarios['banco']); ?></p>
            <p><strong>Agência:</strong> <?php echo htmlspecialchars($dadosBancarios['agencia']); ?></p>
            <p><strong>Conta:</strong> <?php echo htmlspecialchars($dadosBancarios['conta']); ?></p>
            <p><strong>Nome do Titular:</strong> <?php echo htmlspecialchars(trim($usuario['nome'] . ' ' . ($usuario['sobrenome'] ?? ''))); ?></p>
            <p><strong>Chave Pix:</strong> <?php echo htmlspecialchars($chavePixDados['chave']); ?> </p>
            <p class="valor"><strong>Valor a Pagar:</strong> R$ <?php echo number_format($valor, 2, ',', '.'); ?></p>
        <?php else: ?>
            <p class="erro">Nenhum dado bancário ou chave Pix encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
