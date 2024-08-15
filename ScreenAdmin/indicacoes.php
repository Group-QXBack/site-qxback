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
        i.telefone_empresa,
        i.celular_empresa,
        i.email_empresa,
        i.data_indicacao,
        i.status,
        c.nome_contato,
        c.cargo_contato,
        c.celular_contato,
        c.email_contato,
        u.nome AS usuario_nome,
        a.nome AS area_nome
    FROM 
        indicacoes i
        LEFT JOIN contatos c ON i.id = c.indicacao_id
        LEFT JOIN usuarios u ON i.usuario_id = u.id
        LEFT JOIN indicacoes_areas ia ON i.id = ia.indicacao_id
        LEFT JOIN areas a ON ia.area_id = a.id
    WHERE 
        i.status = ?
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
            font-family: 'Red Hat Display', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        header {
            background-color: #333;
            padding: 10px 0;
            text-align: center;
        }

        header .logo {
            max-width: 150px;
            height: auto;
        }

        .corpo_principal {
            display: flex;
            flex-direction: column;
            margin: 20px;
        }

        .container {
            display: flex;
            flex-direction: row;
        }

        section {
            width: 200px;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }

        section a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background-color 0.3s, color 0.3s;
        }

        section a:hover {
            background-color: #f0f0f0;
        }

        section a i {
            font-size: 20px;
            margin-right: 10px;
        }

        .linha-vertical {
            border-left: 2px solid #ddd;
            height: auto;
            margin: 0 20px;
        }

        .indicacoes-container {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .indicacoes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .indicacoes-table th, .indicacoes-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .indicacoes-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .indicacoes-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .indicacoes-table tr:hover {
            background-color: #f1f1f1;
        }

        .btn-acao {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            margin: 2px;
            display: inline-block;
            text-align: center;
        }

        .btn-aceitar {
            background-color: #28a745;
        }

        .btn-negado {
            background-color: #dc3545;
        }

        .btn-view {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            color: #007bff;
            background-color: #e7f0ff;
            border: 1px solid #007bff;
            font-weight: bold;
        }

        .btn-view:hover {
            background-color: #d0e3ff;
            color: #0056b3;
        }

        .success-message, .error-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        .no-data-message {
            padding: 15px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 8px;
            text-align: center;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
            text-align: center;
        }

        footer .primeiro-rodape, footer .segundo-rodape {
            margin: 0;
            padding: 10px;
        }

        footer .voltar-ao-topo {
            margin-bottom: 10px;
        }

        footer .icons a {
            color: #fff;
            margin: 0 10px;
            text-decoration: none;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="Logo da página">
    </header>
    <div class="corpo_principal">
        <article>
            <h1>Indicações</h1>
            <hr>
        </article>
        <div class="container">
            <?php if ($message): ?>
                <div class="<?php echo $message['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
                    <?php echo htmlspecialchars($message['text']); ?>
                </div>
            <?php endif; ?>

            <div class="linha-vertical"></div>
            <article class="indicacoes-container">
                <form method="get" action="indicacoes.php">
                    <label for="status">Filtrar por Status:</label>
                    <select name="status" id="status">
                        <option value="Em Andamento" <?php echo $status_filter === 'Em Andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                        <option value="Aceita" <?php echo $status_filter === 'Aceita' ? 'selected' : ''; ?>>Aceita</option>
                        <option value="Negada" <?php echo $status_filter === 'Negada' ? 'selected' : ''; ?>>Negada</option>
                    </select>
                    <button type="submit">Filtrar</button>
                </form>

                <table class="indicacoes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome da Empresa</th>
                            <th>CNPJ</th>
                            <th>Telefone da Empresa</th>
                            <th>Celular da Empresa</th>
                            <th>Email da Empresa</th>
                            <th>Nome do Contato</th>
                            <th>CPF do Contato</th>
                            <th>Cargo do Contato</th>
                            <th>Celular do Contato</th>
                            <th>Email do Contato</th>
                            <th>Área</th>
                            <th>Indicador</th>
                            <th>Status</th>
                            <th>Data da Indicação</th>
                            <?php if ($status_filter === 'Em Andamento'): ?>
                                <th>Ações</th>
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
                                    <td><?php echo htmlspecialchars($row['telefone_empresa']); ?></td>
                                    <td><?php echo htmlspecialchars($row['celular_empresa']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email_empresa']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nome_contato']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cpf']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cargo_contato']); ?></td>
                                    <td><?php echo htmlspecialchars($row['celular_contato']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email_contato']); ?></td>
                                    <td><?php echo htmlspecialchars($row['area_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($row['usuario_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['data_indicacao']); ?></td>
                                    <?php if ($status_filter === 'Em Andamento'): ?>
                                        <td>
                                            <a href="acoes.php?action=aceitar&id=<?php echo $row['id']; ?>" class="btn-acao btn-aceitar">Aceitar</a>
                                            <a href="acoes.php?action=negar&id=<?php echo $row['id']; ?>" class="btn-acao btn-negado">Negar</a>
                                        </td>
                                    <?php endif; ?>
                                    <?php if ($filter_type === 'empresa'): ?>
                                        <td><a href="detalhes_empresa.php?id=<?php echo $row['id']; ?>" class="btn-view">Ver Detalhes</a></td>
                                    <?php elseif ($filter_type === 'contato'): ?>
                                        <td><a href="detalhes_contato.php?id=<?php echo $row['id']; ?>" class="btn-view">Ver Detalhes</a></td>
                                    <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo $filter_type ? '16' : '15'; ?>" class="no-data-message">Não há nenhuma indicação <?php echo $status_filter === 'Em Andamento' ? 'em andamento' : 'para o status selecionado'; ?>.</td>
                            </tr>
                        <?php endif; ?>
                        <a href="index.php">Voltar</a>
                    </tbody>
                </table>
            </article>
        </div>
    </div>
    <footer class="primeiro-rodape">
        <div class="voltar-ao-topo">
            <hr>
            <i class="fa-solid fa-arrow-up-long" style="color: #00ff00;"></i>
            <a style="cursor: pointer;" onclick="subiraoTopo();">Voltar ao topo</a>
            <hr>
        </div>
        <article>
            <div class="container-texto">
                <div class="primeiro-txt">
                    <h3>
                        <strong style="cursor: default; color: rgb(255, 255, 255);">QXBack</strong>
                    </h3>
                    <p>
                        <a href="#">Programa</a>
                        <a href="#">Como indicar?</a>
                        <a href="#">Portal de indicações</a>
                    </p>
                </div>
                <div class="segundo-txt">
                    <h3>
                        <strong style="cursor: default; color: rgb(255, 255, 255);">Serviços</strong>
                    </h3>
                    <p>
                        <a href="#">Atendimento Virtual</a>
                        <a href="#">Feedback</a>
                    </p>
                </div>
            </div>
        </article>
    </footer>
    <footer class="segundo-rodape">
        <div class="copyright">
            <nav class="icons">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-square-instagram"></i></a>
            </nav>
            <p>Política de Privacidade</p>
        </div>
    </footer>
    <script>
        function subiraoTopo() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$conexao->close();
?>
