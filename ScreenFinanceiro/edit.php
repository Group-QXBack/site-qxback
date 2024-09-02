<?php
session_start();

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']); 

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

$usuario = $_SESSION['usuario'];

// Adiciona a verificação de permissão
$show_permission_error = false;
if ($usuario['tipo_conta'] === 'financeiro' && isset($_GET['action']) && $_GET['action'] === 'editar') {
    $show_permission_error = true;
}

if ($usuario['tipo_conta'] !== 'financeiro') {
    header("Location: ../ScreenFinanceiro/indicacoes.php");
    exit();
}

$status_filter = $_GET['status'] ?? 'Todas';
$filter_type = $_GET['filter_type'] ?? '';
$valid_statuses = ['Em Andamento', 'Aceita', 'Negada', 'Todas'];

if (!in_array($status_filter, $valid_statuses)) {
    $status_filter = 'Todas';
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
";

if ($status_filter !== 'Todas') {
    $sql .= " WHERE i.status = ?";
}

$sql .= "
    GROUP BY
        i.id, i.nome_empresa, i.cnpj, i.cpf, i.data_indicacao, i.ultima_atualizacao, i.status, c.nome_contato, c.cargo_contato, c.celular_contato, c.email_contato, u.nome
";

$stmt = $conexao->prepare($sql);

if ($status_filter !== 'Todas') {
    $stmt->bind_param('s', $status_filter);
}

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
        .permission-error {
            background-color: rgba(0, 0, 0, 0.8);
            color: #ff4d4d;
            padding: 20px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 600px;
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
        <?php if ($show_permission_error): ?>
            <div class="permission-error">
                <h2>Você não tem permissão para editar indicações</h2>
                <p>Seu tipo de conta não permite realizar essa ação. Por favor, entre em contato com o administrador para mais informações.</p>
                <div class="bottom-buttons">
                    <a href="indicacoes.php">Voltar</a>
                </div>
            </div>
        <?php else: ?>
        <?php endif; ?>
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
