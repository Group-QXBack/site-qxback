<?php
session_start();
include_once('../ScreenCadastro/config.php'); 

if (!isset($_SESSION['usuario'])) {
    header('Location: login.html');
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];

$query = "
    SELECT i.nome_empresa, i.cnpj, i.telefone_empresa, i.celular_empresa, i.email_empresa, 
           i.data_indicacao, i.status, i.valor_pendente, 
           GROUP_CONCAT(a.nome SEPARATOR ', ') AS areas
    FROM indicacoes i
    JOIN indicacoes_areas ia ON i.id = ia.indicacao_id
    JOIN areas a ON ia.area_id = a.id
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
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-em-andamento {
            color: #f39c12;
            font-weight: bold;
        }
        .status-confirmada {
            color: #2ecc71;
            font-weight: bold;
        }
        .status-rejeitada {
            color: #e74c3c;
            font-weight: bold;
        }
        .valor-pendente {
            text-align: right;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
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
                    <th>Telefone</th>
                    <th>Celular</th>
                    <th>E-mail</th>
                    <th>Área Indicada</th>
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
                            <td>{$row['telefone_empresa']}</td>
                            <td>{$row['celular_empresa']}</td>
                            <td>{$row['email_empresa']}</td>
                            <td>{$row['areas']}</td>
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
    </div>
    <footer>
        &copy; 2024 QXBack. Todos os direitos reservados.
    </footer>
</body>
</html>
