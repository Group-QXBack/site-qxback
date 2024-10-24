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
    // Agora o valor do resgate será o saldo total do usuário
    $valor = $saldo;

    if ($valor < 10) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Você precisa ter pelo menos R$ 10,00 para solicitar resgate.'];
    } else {
        $sql = "INSERT INTO solicitações_resgate (usuario_id, valor) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('id', $userId, $valor);

        if ($stmt->execute()) {
            // Atualiza o saldo do usuário
            $novoSaldo = $saldo - $valor;
            $sql = "UPDATE usuarios SET saldo = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('di', $novoSaldo, $userId);
            $stmt->execute();

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Solicitação de resgate realizada com sucesso!'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro ao processar sua solicitação.'];
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
<link rel="stylesheet" href="../ScreenUser/styleResgate.php">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<head>
    <title>Solicitar Resgate</title>
    <meta charset="UTF-8">
</head>
<body>

<header>
    <img src="../imagens/logobranca1.png" class="logo" alt="logo">
    <div class="menu-icon" onclick="toggleMenu()">☰</div>
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
    <div class="resgatar">
    <img src="../imagens/Porquinho qx.png" class="mascote" alt="mascote">
    <h1>Você já pode resgatar seus R$<?php echo number_format($saldo, 2, ',', '.'); ?>.</h1>
    <p>Para aproveitar seu dinheiro como quiser, é só confirmar os seus dados bancários abaixo.</p>
    </div>
        <div class="informações">
            <div class="titulo">
                <h1>Confire as Informações:</h1>
                
            </div>
            <div class="dados-usuario">
                <div class="conta-bancaria">
                <h4>Conta Bancária</h4>
                <p>Banco:<?php echo isset($conta_bancaria['banco']) ? htmlspecialchars($conta_bancaria['banco']) : ''; ?></p>
                <p>Agência: <?php echo htmlspecialchars($contas_bancarias['agencia'] ?? ''); ?></p>
                <p>Conta:</p>
                <a href="../ScreenUser/dados_bancario.php" class="btn-resgate">Editar Conta Bancária</a>
                </div>
            
            <div class="endereco-usuario">
                <h4>Seu Endereço</h4>
                <p>CEP:</p>
                <p>Endereço:</p>
                <p>Numero:</p>
                <a class="btn-resgate">Editar Endereço</a>
            </div>           
        </div>
        </div>
        <button type="submit">Solicitar Resgate</button>
    </form>
<?php else: ?>
    <div id="telaSemResgate">
        <img src="../imagens/mascoteQX.png" class="mascote" alt="mascote">
        <h1 style="color: #42FF00;">Você não possui saldo para resgatar</h1>
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
<script>
    document.getElementById('moneyInput').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, ''); 
    value = (value / 100).toFixed(2) + '';         // Divide por 100 e adiciona duas casas decimais
    value = value.replace('.', ',');               // Substitui o ponto por vírgula
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Adiciona pontos para milhares
    e.target.value = 'R$ ' + value;                // Adiciona o prefixo R$
});
function toggleMenu() {
        var sidebar = document.querySelector("nav.menu-lateral");
        sidebar.classList.toggle("expandir");
        }

</script>
<footer>
        <div class="footerContainer">
            <div class="socialIcons">
                <a href=""><i class="fa-brands fa-facebook"></i></a>
                <a href=""><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="footerBottom">
            <p>Copyright &copy;2024; Designed by <span class="designer">3Point</span></p>
        </div>
    </footer>
</body>
</html>
