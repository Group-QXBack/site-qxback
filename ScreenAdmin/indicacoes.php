<?php
session_start();

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']); 

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

$status_filter = $_GET['status'] ?? 'Em Andamento';
$filter_type = $_GET['filter_type'] ?? '';
$valid_statuses = ['Em Andamento', 'Aceita', 'Negada'];

if (!in_array($status_filter, $valid_statuses)) {
    $status_filter = 'Em Andamento';
}

$sql = "
    SELECT 
        i.id,
        i.nome_empresa,
        i.cnpj,
        i.cpf,
        i.data_indicacao,
        i.ultima_atualizacao,
        i.status,
        c.nome_contato,
        c.cargo_contato,
        c.celular_contato,
        c.email_contato,
        u.nome AS usuario_nome,
        GROUP_CONCAT(s.nome SEPARATOR ', ') AS servico_nome
    FROM 
        indicacoes i
        LEFT JOIN contatos c ON i.id = c.indicacao_id
        LEFT JOIN usuarios u ON i.usuario_id = u.id
        LEFT JOIN indicacoes_servicos is_servicos ON i.id = is_servicos.indicacao_id
        LEFT JOIN servicos s ON is_servicos.servicos_id = s.id
    WHERE 
        i.status = ?
    GROUP BY
        i.id, i.nome_empresa, i.cnpj, i.cpf, i.data_indicacao, i.ultima_atualizacao, i.status, c.nome_contato, c.cargo_contato, c.celular_contato, c.email_contato, u.nome
";

$stmt = $conexao->prepare($sql);
$stmt->bind_param('s', $status_filter);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/icon_uu.webp" type="image/x-icon">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/af6c14a78e.js" crossorigin="anonymous"></script>
    <title>Indicações</title>
    <style> 
            body {
                font-family: 'Red Hat Display', Arial, sans-serif;
                background: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
                color: whitesmoke;
                margin: 0;
                padding: 0;
                text-align: center;
            }

            header {
                padding: 20px;
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
                background: linear-gradient(to right, rgb(255, 255, 255), rgb(0, 0, 0));
                margin: 20px auto;
                width: 80%;
            }

            .search-container {
                margin-bottom: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .search-container label {
                margin-right: 10px;
            }

            .search-container select, .search-container button {
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ddd;
                font-size: 16px;
            }

            .search-container select {
                margin-right: -1px;
                flex: 1;
                max-width: 200px;
            }

            .search-container button {
                background-color: rgba(0, 0, 0, 0.3);
                color: #fff;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .search-container button:hover {
                background-color: rgb(75, 198, 133);
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

            .btn-acao.btn-aceitar {
                background-color: #28a745;
            }

            .btn-acao.btn-negado {
                background-color: #dc3545;
            }

            .btn-acao:hover {
                background-color: rgb(75, 198, 133);
            }

            .btn-view {
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

            .btn-view:hover {
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
            </style>
</head>
<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="Logo da página">
    </header>
    <div class="container">
        <article>
            <h1>Indicações</h1>
            <hr>
        </article>
        <?php if ($message): ?>
            <div class="<?php echo $message['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="search-container">
            <form method="get" action="indicacoes.php">
                <label for="status">Filtrar por Status:</label>
                <select name="status" id="status">
                    <option value="Em Andamento" <?php echo $status_filter === 'Em Andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                    <option value="Aceita" <?php echo $status_filter === 'Aceita' ? 'selected' : ''; ?>>Aceita</option>
                    <option value="Negada" <?php echo $status_filter === 'Negada' ? 'selected' : ''; ?>>Negada</option>
                </select>
                <button type="submit">Filtrar</button>
            </form>
        </div>

        <table class="indicacoes-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Empresa</th>
                    <th>CNPJ</th>
                    <th>Nome do Contato</th>
                    <th>Celular do Contato</th>
                    <th>Email do Contato</th>
                    <th>Serviço(s)</th>
                    <th>Indicador</th>
                    <th>Data da Indicação</th>
                    <th>Última Atualização</th>
                    <?php if ($status_filter === 'Em Andamento'): ?>
                        <th>Ações</th>
                    <?php elseif ($status_filter === 'Aceita' || $status_filter === 'Negada'): ?>
                        <th>Editar</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nome_empresa']); ?></td>
                            <td><?php echo htmlspecialchars($row['cnpj']); ?></td>
                            <td><?php echo htmlspecialchars($row['nome_contato']); ?></td>
                            <td><?php echo htmlspecialchars($row['celular_contato']); ?></td>
                            <td><?php echo htmlspecialchars($row['email_contato']); ?></td>
                            <td><?php echo htmlspecialchars($row['servico_nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['usuario_nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_indicacao']); ?></td>
                            <td><?php echo htmlspecialchars($row['ultima_atualizacao']); ?></td>
                            <?php if ($status_filter === 'Em Andamento'): ?>
                                <td class="actions-column">
                                    <a href="#" class="btn-acao btn-aceitar" data-action="aceitar" data-id="<?php echo $row['id']; ?>">Aceitar</a>
                                    <a href="#" class="btn-acao btn-negado" data-action="negar" data-id="<?php echo $row['id']; ?>">Negar</a>
                                </td>
                            <?php elseif ($status_filter === 'Aceita' || $status_filter === 'Negada'): ?>
                                <td>
                                    <a href="editar_indicacao.php?id=<?php echo $row['id']; ?>" class="btn-view">Editar</a>
                                </td>
                            <?php endif; ?>
                            <?php if ($filter_type === 'empresa'): ?>
                                <td><a href="detalhes_empresa.php?id=<?php echo $row['id']; ?>" class="btn-view">Ver Detalhes</a></td>
                            <?php elseif ($filter_type === 'contato'): ?>
                                <td><a href="detalhes_contato.php?id=<?php echo $row['id']; ?>" class="btn-view">Ver Detalhes</a></td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo $filter_type ? '16' : '15'; ?>" class="no-data-message">Não há nenhuma indicação <?php echo $status_filter === 'Em Andamento' ? 'em andamento' : 'para o status selecionado'; ?>.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="bottom-buttons">
            <a href="index.php">Voltar</a>
        </div>
    </div>

    <footer class="primeiro-rodape"></footer>
    <footer class="segundo-rodape"></footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.btn-acao');

            buttons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const action = this.getAttribute('data-action');
                    const id = this.getAttribute('data-id');
                    const confirmation = confirm(`Tem certeza que deseja ${action} esta indicação?`);

                    if (confirmation) {
                        window.location.href = `acoes.php?action=${action}&id=${id}`;
                    }
                });
            });
        });
    </script>
</body>
</html>