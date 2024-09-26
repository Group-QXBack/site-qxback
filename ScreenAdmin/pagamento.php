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

if ($idSolicitacao) {
    // Buscar dados da solicitação de resgate
    $sql = "SELECT valor, usuario_id FROM solicitações_resgate WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $idSolicitacao);
    $stmt->execute();
    $solicitacao = $stmt->get_result()->fetch_assoc();

    if ($solicitacao) {
        $valor = number_format((float)$solicitacao['valor'], 2, '.', '');
        $usuarioId = (int)$solicitacao['usuario_id'];

        // Buscar dados bancários
        $sql = "SELECT * FROM contas_bancarias WHERE usuario_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $dadosBancarios = $stmt->get_result()->fetch_assoc();

        // Buscar chave Pix do usuário
        $sql = "SELECT tipo_chave, chave FROM chaves_pix WHERE usuario_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $chavePixDados = $stmt->get_result()->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagar Solicitação</title>
</head>
<body>
    <h1>Pagar Solicitação</h1>

    <?php if ($dadosBancarios && $chavePixDados): ?>
        <h2>Dados Bancários do Usuário</h2>
        <p><strong>Banco:</strong> <?php echo htmlspecialchars($dadosBancarios['banco']); ?></p>
        <p><strong>Agência:</strong> <?php echo htmlspecialchars($dadosBancarios['agencia']); ?></p>
        <p><strong>Conta:</strong> <?php echo htmlspecialchars($dadosBancarios['conta']); ?></p>
        <p><strong>Nome do Titular:</strong> <?php echo htmlspecialchars($dadosBancarios['nome_titular']); ?></p>
        <p><strong>Valor a Pagar:</strong> R$ <?php echo number_format($valor, 2, ',', '.'); ?></p>

        <?php
        $chavePix = htmlspecialchars($chavePixDados['chave']);
        $valorFormatado = str_replace(',', '', $valor);      
        ?>
    <?php else: ?>
        <p>Nenhum dado bancário ou chave Pix encontrado.</p>
    <?php endif; ?>
</body>
</html>
