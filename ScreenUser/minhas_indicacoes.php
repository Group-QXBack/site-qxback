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
    <title>Minhas Indicações</title>
    <style>
            body {
                font-family: 'Red Hat Display', Arial, sans-serif;
                background-color: #343434;
                color: whitesmoke;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            .img_logo_header{
    width: 180px;
}
.header,
.navigation_header{
    display: flex;
    flex-direction: row;
    align-items: center;
}
.header{
    background-color: #1d1d1d;
    justify-content: space-between;
    padding: 0 10%;
    height: 4em;
}
.navigation_header{
    gap: 3em;
    z-index: 2;
}
.content{
    padding-top: 5em;
    text-align: center;
    height: 100vh;
    transition: 1s;
}
.navigation_header a{
    text-decoration: none;
    color: var(--color-white);
    transition: 1s;
    font-weight: bold;
}
.navigation_header a:hover{
    color: var(--color-white);
}
.btn_icon_header{
    background: transparent;
    border: none;
    color: var(--color-white);
    cursor: pointer;
    display: none;
}

            .logo {
                max-width: 200px;
                height: auto;
            }

            .container {
                margin: 20px auto;
                max-width: 1200px;
                padding: 0 20px;
            }

            h1 {
                margin-bottom: 20px;
                font-size: 2.5em;
                color: #fff;
            }

            hr {
                border: 0;
                height: 1px;
                background-color: rgb(54, 54, 54);
                margin: 20px auto;
                width: 80%;
            }

            table {
                width: 100%;
                margin: 20px 0;
                border-collapse: collapse;
                background-color: rgba(0, 0, 0, 0.3);
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            th, td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: rgba(0, 0, 0, 0.5);
                color: #fff;
            }

            tr:nth-child(even) {
                background-color: rgba(255, 255, 255, 0.1);
            }

            tr:hover {
                background-color: rgba(255, 255, 255, 0.2);
            }

            .status-em-andamento {
                color: #ffcc00; 
            }

            .status-confirmada {
                color: #28a745; 
            }

            .status-rejeitada {
                color: #dc3545;
            }

            .valor-pendente {
                color: #f39c12; 
            }

            .actions-column a, .btn-acao {
                text-decoration: none;
                color: #fff;
                padding: 10px;
                background-color: rgba(0, 0, 0, 0.3);
                border-radius: 5px;
                transition: background-color 0.3s;
                display: inline-block;
                margin: 0 5px;
                font-size: 14px;
                text-align: center;
            }

            .btn-acao:hover {
                background-color: rgb(75, 198, 133);
            }

            .no-data-message {
                text-align: center;
                color: #aaa;
                padding: 20px;
                font-size: 16px;
            }

            .bottom-buttons {
                margin-top: 20px;
                text-align: center;
            }

            .bottom-buttons a {
                padding: 10px 20px;
                background-color: rgba(0, 0, 0, 0.3);
                color: #fff;
                border-radius: 5px;
                transition: background-color 0.3s;
                font-size: 16px;
                text-decoration: none;
                display: inline-block;
            }

            .bottom-buttons a:hover {
                background-color: rgb(75, 198, 133);
            }

            footer {
                padding: 20px;
                background-color: rgba(0, 0, 0, 0.3);
            }

            .expand-btn {
                cursor: pointer;
                font-size: 18px;
                color: #007bff;
                border: none;
                background: none;
            }

            .details-row {
                display: none;
                background-color: rgba(0, 0, 0, 0.5);
                color: #fff;
            }

            .details-row td {
                padding: 10px;
                border: none;
            }

            .details-row ul {
                list-style-type: none;
                padding: 0;
            }

            .details-row li {
                margin-bottom: 5px;
            }

            .sem-indicacoes{

            }

    @media screen and (max-width: 768px) {
    .navigation_header{
        position: absolute;
        flex-direction: column !important;
        top: 0;
        background: var(--color-dark5);
        height: 100%;
        width: 35vw;
        padding: 1em;
        animation-duration: 1s;
        margin-left: -100vw;
    }
    .btn_icon_header{
        display: block;
    }
}
    @keyframes showSidebar {
    from {margin-left: -100vw;}
    to {margin-left: -10vw;}
}
    </style>
</head>
<body>
    <div class="header" id="header">
        <button onclick="toggleSidebar()" class="btn_icon_header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>
        <div class="logo_header">
            <img src="../imagens/logobranca1.png" alt="Logo" class="img_logo_header">
        </div>
        <div class="navigation_header" id="navigation_header">
            <button onclick="toggleSidebar()" class="btn_icon_header">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
            <a href="./index.php" id="btn-minhaConta" style="border-radius: 14px;">
            <p>Minha Conta</p>
            </a>
            <a href="./minhas_indicacoes.php">
            <p>Minhas Indicações</p>
            </a>
            <a href="./indicar.php">
            <p>Indicar</p>
            </a>
        </div>
    </div>
    <div class="container">
        <?php if (!empty($indicacoes)): ?>
    <!-- Tabela de Indicações -->
    <table>
        <thead>
            <tr>
                <th>Nome da Empresa</th>
                <th>CNPJ</th>
                <th>Valor Pendente</th>
                <th>Data de Indicação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
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
        </tbody>
                </table>
            <?php else: ?>
    <!-- Exibir imagem quando não houver indicações -->
    <div class="sem-indicacoes">
        <img src="../imagens/img-espera.png" alt="Nenhuma indicação encontrada">
        <h2>Nenhuma indicação encontrada</h2>
    </div>
<?php endif; ?>


        <div class="bottom-buttons">
            <a href="index.php">Voltar</a>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 QXBack. Todos os direitos reservados.</p>
    </footer>
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
