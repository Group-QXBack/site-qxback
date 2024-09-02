<?php
session_start();
include_once('../ScreenCadastro/config.php'); 

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'user') {
    header('Location: login.html');
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];

$query = "
    SELECT i.nome_empresa, i.cnpj,
           i.data_indicacao, i.status, i.valor_pendente, 
           GROUP_CONCAT(s.nome SEPARATOR ', ') AS servicos
    FROM indicacoes i
    JOIN indicacoes_servicos i_servicos ON i.id = i_servicos.indicacao_id
    JOIN servicos s ON i_servicos.servicos_id = s.id
    WHERE i.usuario_id = ?
    GROUP BY i.id
";

$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$indicacoes = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
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
        <h1>Minhas Indicações</h1>
    </header>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Nome da Empresa</th>
                    <th>CNPJ</th>
                    <th>Serviços Indicados</th>
                    <th>Status</th>
                    <th>Valor Pendente</th>
                    <th>Data da Indicação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($indicacoes)) {
                    foreach ($indicacoes as $row) {
                        $statusClass = '';
                        switch ($row['status']) {
                            case 'Em Andamento':
                                $statusClass = 'status-em-andamento';
                                break;
                            case 'Confirmada':
                                $statusClass = 'status-confirmada';
                                break;
                            case 'Rejeitada':
                                $statusClass = 'status-rejeitada';
                                break;
                        }
                        echo "<tr>
                            <td>{$row['nome_empresa']}</td>
                            <td>{$row['cnpj']}</td>
                            <td>{$row['servicos']}</td>
                            <td class='$statusClass'>{$row['status']}</td>
                            <td class='valor-pendente'>R$ {$row['valor_pendente']}</td>
                            <td>{$row['data_indicacao']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Nenhuma indicação encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button><a href="index.php">Voltar</a></button>
    </div>
    <footer>
        &copy; 2024 QXBack. Todos os direitos reservados.
    </footer>
</body>
</html>
