<?php
session_start(); 

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'inativo') {
    header("Location: ../ScreenUser/index.html");
    exit();
}

require '../ScreenCadastro/config.php'; 

$usuario_id = $_SESSION['usuario']['id'];

$sql = "SELECT motivo, status, data_solicitacao FROM solicitacoes_inativacao WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $solicitacao = $result->fetch_assoc();
} else {
    $solicitacao = null;
}

$stmt->close();
$conexao->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Status da Solicitação</title>
</head>
<body>
    <div class="login">
        <div class="left-login">
            <img src="../imagens/qxback.png" alt="qxback">
        </div>
        <div class="right-login">
            <div class="card-login">
                <h1>Status da Solicitação</h1>
                <?php if ($solicitacao): ?>
                    <p><strong>Motivo da Solicitação:</strong> <?php echo htmlspecialchars($solicitacao['motivo']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($solicitacao['status']); ?></p>
                    <p><strong>Data da Solicitação:</strong> <?php echo htmlspecialchars($solicitacao['data_solicitacao']); ?></p>
                <?php else: ?>
                    <p>Você ainda não fez nenhuma solicitação de reativação.</p>
                <?php endif; ?>
                <a href="../ScreenUser/logout.php" id="btn-sair">
                    <i class="fi fi-br-exit"></i>
                    <p>Sair</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
