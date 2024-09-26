<?php
session_start();
include '../ScreenCadastro/config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenLogin/index.html");
    exit();
}

$usuario = $_SESSION['usuario'];
$userId = $usuario['id'];
$sql = "SELECT saldo FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $saldo = $usuario['saldo'];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Usuário não encontrado.'];
    header("Location: area_usuario.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valor = $_POST['valor'];

    if ($valor < 1) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Valor mínimo de resgate é R$ 1,00.'];
    } elseif ($valor > $saldo) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Valor solicitado excede o saldo disponível.'];
    } else {
        $sql = "INSERT INTO solicitações_resgate (usuario_id, valor) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('id', $userId, $valor);

        if ($stmt->execute()) {
            $novoSaldo = $saldo - $valor;
            $sql = "UPDATE usuarios SET saldo = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('di', $novoSaldo, $userId);
            $stmt->execute();

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Solicitação de resgate criada com sucesso.'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro ao criar solicitação de resgate.'];
        }
    }

    header("Location: solicitar_resgate.php");
    exit();
}

$sql = "SELECT * FROM solicitações_resgate WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Solicitar Resgate</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Solicitar Resgate</h1>
    <p>Seu saldo disponível: R$ <?php echo number_format($saldo, 2, ',', '.'); ?></p>
    <form method="POST">
        <label for="valor">Valor do Resgate:</label>
        <input type="number" name="valor" min="1" max="<?php echo $saldo; ?>" required>
        <button type="submit" <?php echo $saldo < 1 ? 'disabled' : ''; ?>>Solicitar Resgate</button>
    </form>

    <?php if ($saldo < 1): ?>
        <p style="color: red;">Você não tem saldo disponível para solicitar resgate.</p>
    <?php endif; ?>

    <h2>Minhas Solicitações de Resgate</h2>
    <table>
        <thead>
            <tr>
                <th>Valor</th>
                <th>Data</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['data_solicitacao'])); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
            <?php endwhile; ?>
            <?php if ($result->num_rows == 0): ?>
                <tr>
                    <td colspan="4">Nenhuma solicitação encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
