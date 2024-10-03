<?php
session_start();
include_once('../ScreenCadastro/config.php'); 

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'user') {
    header('Location: login.html');
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];

$query = "
    SELECT i.id, i.nome_empresa, i.cnpj, 
           i.data_indicacao, 
           i.valor_pendente
    FROM indicacoes i
    WHERE i.usuario_id = ?
";

$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$indicacoes = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

$indicacoes_servicos = [];
foreach ($indicacoes as $indicacao) {
    $query = "
        SELECT s.nome AS servico_nome, i_servicos.status
        FROM indicacoes_servicos i_servicos
        JOIN servicos s ON i_servicos.servicos_id = s.id
        WHERE i_servicos.indicacao_id = ?
    ";

    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $indicacao['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $indicacoes_servicos[$indicacao['id']] = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
}

$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../ScreenUser/styleIndicacoes.php">
    <title>Minhas Indicações</title>
</head>
<body>
<header>
        <img src="../imagens/logobranca1.png" class="logo" alt="logo">
    </header>
    <nav class="menu-lateral">
        <div class="btn-expandir">
            <i class="bi bi-list"></i>
        </div>
        <ul>
            <li class="item-menu">
                <a href="index.php">
                    <span class="icon"><i class="bi bi-person-fill"></i></span>
                    <span class="txt-link">Perfil</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="../ScreenUser/minhas_indicacoes.php">
                    <span class="icon"><i class="bi bi-journal-plus"></i></span>
                    <span class="txt-link">Minhas Indicações</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="../ScreenUser/indicarUsuario.php">
                    <span class="icon"><i class="bi bi-plus-square"></i></span>
                    <span class="txt-link">Indicar</span>
                </a>
            </li>
            <li class="item-menu">
            <a href="../ScreenUser/solicitar_resgate.php">
                    <span class="icon"><i class="bi bi-coin"></i></span>
                    <span class="txt-link">Solicitar Resgate</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="../ScreenUser/logout.php">
                    <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="txt-link">Sair</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="container">
        <h1>Minhas Indicações</h1>
        <table>
            <thead>
                <tr>
                    <th>Nome da Empresa</th>
                    <th>CNPJ</th>
                    <th>Valor Pendente</th>
                    <th>Data da Indicação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($indicacoes)): ?>
                    <?php foreach ($indicacoes as $indicacao): ?>
                        <tr class="main-row" data-id="<?php echo htmlspecialchars($indicacao['id']); ?>">
                            <td><?php echo htmlspecialchars($indicacao['nome_empresa']); ?></td>
                            <td><?php echo htmlspecialchars($indicacao['cnpj']); ?></td>
                            <td class="valor-pendente">R$ <?php echo number_format($indicacao['valor_pendente'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($indicacao['data_indicacao']); ?></td>
                            <td><button class="expand-btn">+</button></td>
                        </tr>
                        <tr class="details-row" id="details-<?php echo htmlspecialchars($indicacao['id']); ?>">
                            <td colspan="7">
                                <strong>Serviços Detalhados:</strong>
                                <ul>
                                    <?php 
                                    if (isset($indicacoes_servicos[$indicacao['id']])) {
                                        foreach ($indicacoes_servicos[$indicacao['id']] as $servico) {
                                            echo "<li>" . htmlspecialchars($servico['servico_nome']) . " - " . htmlspecialchars($servico['status']) . "</li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-data-message">Nenhuma indicação encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="bottom-buttons">
            <a href="index.php">Voltar</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.expand-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    var mainRow = this.closest('.main-row');
                    var detailsRow = document.getElementById('details-' + mainRow.dataset.id);

                    if (detailsRow.style.display === 'none' || !detailsRow.style.display) {
                        detailsRow.style.display = 'table-row';
                        this.textContent = '-';
                    } else {
                        detailsRow.style.display = 'none';
                        this.textContent = '+';
                    }
                });
            });
        });
    </script>
</body>
</html>
