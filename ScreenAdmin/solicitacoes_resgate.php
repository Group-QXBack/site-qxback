<?php
session_start();
include '../ScreenCadastro/config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenUser/index.html");
    exit();
}

$sql = "SELECT sr.*, u.nome FROM solicitações_resgate sr JOIN usuarios u ON sr.usuario_id = u.id WHERE sr.status = 'pendente'";
$result = $conexao->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idSolicitacao = $_POST['id'];
    $acao = $_POST['acao'];

    if ($acao === 'pagar') {
        $sql = "UPDATE solicitações_resgate SET status = 'pago' WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $idSolicitacao);
        $stmt->execute();

        header("Location: pagamento.php?id=" . $idSolicitacao);
        exit();
    } elseif ($acao === 'rejeitar') {
        $sql = "UPDATE solicitações_resgate SET status = 'rejeitado' WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $idSolicitacao);
        $stmt->execute();
    }

    header("Location: solicitacoes_resgate.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Solicitações de Resgate</title>
</head>
<body>
    <header>
        <h1>Solicitações de Resgate</h1>
    </header>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Valor</th>
                    <th>Data da Solicitação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td>R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['data_solicitacao'])); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="acao" value="pagar">Pagar</button>
                                <button type="submit" name="acao" value="rejeitar">Rejeitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
