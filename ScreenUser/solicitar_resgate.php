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
    header("Location: solicitar_resgate.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valor = $_POST['valor'];

    if ($valor < 10) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Valor mínimo de resgate é R$ 10,00.'];
    } elseif ($valor > $saldo) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Você não tem saldo suficiente para solicitar R$ ' . number_format($valor, 2, ',', '.') . '. Seu saldo atual é R$ ' . number_format($saldo, 2, ',', '.') . '.'
        ];
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

$temDinheiroParaResgatar = $saldo >= 10;

$sql = "SELECT * FROM solicitações_resgate WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="../ScreenUser/resgate.php">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<head>
    <title>Solicitar Resgate</title>
    <meta charset="UTF-8">
</head>
<body>

<header>
    <img src="../imagens/logobranca1.png" class="logo" alt="logo">
    <nav class="menu-lateral">
        <div class="btn-expandir">
            <i class="bi bi-list"></i>
        </div>
        <ul>
            <li class="item-menu"><a href="index.php"><span class="icon"><i class="bi bi-person-fill"></i></span><span class="txt-link">Perfil</span></a></li>
            <li class="item-menu"><a href="../ScreenUser/minhas_indicacoes.php"><span class="icon"><i class="bi bi-journal-plus"></i></span><span class="txt-link">Minhas Indicações</span></a></li>
            <li class="item-menu"><a href="../ScreenUser/indicarUsuario.php"><span class="icon"><i class="bi bi-plus-square"></i></span><span class="txt-link">Indicar</span></a></li>
            <li class="item-menu"><a href="solicitar_resgate.php"><span class="icon"><i class="bi bi-coin"></i></span><span class="txt-link">Solicitar Resgate</span></a></li>
            <li class="item-menu"><a href="logout.php"><span class="icon"><i class="bi bi-box-arrow-right"></i></span><span class="txt-link">Sair</span></a></li>
        </ul>
    </nav>
</header>



<?php if (isset($_SESSION['message'])): ?>
    <div class="message-container <?php echo htmlspecialchars($_SESSION['message']['type'] . '-message'); ?>">
        <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if ($temDinheiroParaResgatar): ?>
    <form method="POST">
    <h1>Solicitar Resgate</h1>
    <p>Seu saldo disponível: R$ <?php echo number_format($saldo, 2, ',', '.'); ?></p>
        <label for="valor">Valor do Resgate:</label>
        <input type="number" name="valor" min="10" max="<?php echo $saldo; ?>" required>
        <button type="submit">Solicitar Resgate</button>
        <button><a href="index.php" class="btn-voltar">Voltar</a></button>
    </form>
<?php else: ?>
    <div id="telaSemResgate">
        <img src="../imagens/mascoteQX.png" class="mascote" alt="mascote">
        <h1>Você não possui saldo para resgatar</h1>
        <p class="paragrafo">Aproveite para fazer indicações e não perder a chance de fazer uma renda extra.</p>
        <a href="../ScreenUser/indicarUsuario.php" class="btn" style="color: #fff">Indicar</a>
    </div>
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
                <td colspan="3">Nenhuma solicitação encontrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>
