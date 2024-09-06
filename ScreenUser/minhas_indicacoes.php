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
            header {
        width: 100%;
        height: 80px;
        background-color: #000000;
        background-size: 200% 200%;    display: flex;
        align-items: center;
        justify-content: space-between;
        color: #ffffff;
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
            .submenu {
        display: flex;
        width: 100%;
        align-items: center;
        justify-content: center;
        background-color: var(--preto);
        font-weight: bold;
        height: 35px;
    }
    .submenu ul {
        display: flex;
        list-style: none;
        padding: 10px;
        gap: 500px;
    }

    .submenu ul li {
        position: relative;
        cursor: pointer;

    }
    .submenu ul li p {
        color:#fff
    }


    .submenu ul li a{
        color: #000000;
        transition: background .3s, color .3s;
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .submenu ul li a:hover,
    .submenu ul li p:hover {
        background-color: #6f886fa8;
        color: #000000;
    }

    .submenu ul ul {
        display: none;
        position: absolute;
        top: 100%;
        width: 220px;
        padding: 5px 10px;
        border-radius: 10px;
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        z-index: 1;
    }

    .submenu ul li:hover ul {
        display: block;
    }

    .submenu ul ul li {
        margin: 0;
    }

    .submenu ul ul li a {
        display: block;
    }
    header>.logo {
            width: 90px;
            height: 40px;
        }

        header .logo {
            padding-left: 50px;
        }
        header>.logo {
        height: 45px;
        width: 150px;
        padding-left: 15px;
        margin-top: 10px;
    }

    </style>
</head>
<body>
<header>
        <img src="../imagens/logobranca1.png" class="logo" alt="Logo da página">
        <div class="submenu">
            <ul>
                <li>
                    <p>Status Indicações <i class="fa-solid fa-chevron-down"></i></p>
                    <ul>
                        <li><a href="minhas_indicacoes.php">Indicações Iniciadas</a></li>
                        <li><a href="minhas_indicacoes.php">Indicações em Andamento</a></li>
                        <li><a href="minhas_indicacoes.php">Indicações Concluídas</a></li>
                    </ul>
                </li>
                <li>
                    <p>Suporte <i class="fa-solid fa-chevron-down"></i></p>
                    <ul id="btn-suporte">
                        <li><a href="atendimento_virtual.html">Atendimento Virtual</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </header>
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
    <footer>
        &copy; 2024 QXBack. Todos os direitos reservados.
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
