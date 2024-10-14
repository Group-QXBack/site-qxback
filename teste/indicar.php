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
                background-color: #161616;
                color: whitesmoke;
                margin: 0;
                padding: 0;
                text-align: center;
            }

            header {
                width: 100%;
                height: 75px;
                background-color: #1d1d1d;
                display: flex;
                justify-content: center;
                align-items: center;
                border-bottom: 2px solid #42FF00;
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
            <option value="Todas" <?php echo $status_filter === 'Todas' ? 'selected' : ''; ?>>Todas</option>
            <option value="pendente" <?php echo $status_filter === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
            <option value="Aceita" <?php echo $status_filter === 'Aceita' ? 'selected' : ''; ?>>Aceita</option>
            <option value="Negada" <?php echo $status_filter === 'Negada' ? 'selected' : ''; ?>>Negada</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>

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
            const servicoId = this.getAttribute('data-id');
            const indicacaoId = this.getAttribute('data-indicacao');
            const statusFilter = new URLSearchParams(window.location.search).get('status');
            const confirmation = confirm(`Tem certeza que deseja ${action} este serviço?`);

            if (confirmation) {
                window.location.href = `acoes.php?action=${action}&servicos_id=${servicoId}&indicacao_id=${indicacaoId}&status=${statusFilter}`;
            }
        });
    });
});
</script>

    </body>
    </html>
