<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] != 'admin') {
    echo "Acesso restrito.";
    exit();
}

include '../ScreenCadastro/config.php';

$sql = "SELECT s.id, s.motivo, s.status, s.data_solicitacao, u.nome, u.email 
        FROM solicitacoes_inativacao s
        JOIN usuarios u ON s.usuario_id = u.id
        WHERE s.status = 'Pendente'";
$result = $conexao->query($sql);

if (!$result) {
    echo "Erro na consulta: " . $conexao->error;
    $conexao->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Reativação</title>
    <style>
 body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
            color: whitesmoke;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h1 {
            margin: 20px 0;
            font-size: 2em;
        }
        .search-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .search-container form {
            display: flex;
            align-items: center;
            max-width: 1000px;
            width: 100%;
        }
        .search-container input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 4px 0 0 4px;
            margin-right: -1px;
            flex: 1;
            font-size: 16px;
        }
        .search-container button, .search-container a {
            padding: 10px 20px;
            border: none;
            border-radius: 0 4px 4px 0;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            text-decoration: none;
        }
        .search-container button:hover, .search-container a:hover {
            background-color: rgb(75, 198, 133);
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
            max-width: 1200px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: rgb(0, 0, 0, 0.5);
        }
        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .actions-column a {
            text-decoration: none;
            color: whitesmoke;
            padding: 8px 16px;
            background-color: rgb(0, 0, 0, 0.3);
            border-radius: 4px;
            transition: background-color 0.3s;
            display: inline-block;
            margin: 0 5px;
        }
        .actions-column a:hover {
            background-color: rgb(75, 198, 133);
        }
        .top-buttons {
            margin: 20px 0;
        }
        .top-buttons a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        .top-buttons a:hover {
            background-color: rgb(75, 198, 133);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #333;
            border: 1px solid #888;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }
        .modal-content h2 {
            margin: 0 0 20px 0;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: rgb(75, 198, 133);
        }
         </style>
</head>
<body>
    <h1>Solicitações de Reativação</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Motivo</th>
                <th>Data da Solicitação</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($solicitacao = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($solicitacao['id']); ?></td>
                    <td><?php echo htmlspecialchars($solicitacao['nome']); ?></td>
                    <td><?php echo htmlspecialchars($solicitacao['email']); ?></td>
                    <td><?php echo htmlspecialchars($solicitacao['motivo']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($solicitacao['data_solicitacao'])); ?></td>
                    <td><?php echo htmlspecialchars($solicitacao['status']); ?></td>
                    <td>
                        <form action="processar_solicitacao.php" method="post" style="display:inline;">
                            <input type="hidden" name="solicitacao_id" value="<?php echo htmlspecialchars($solicitacao['id']); ?>">
                            <button type="submit" name="acao" value="aceitar">Aceitar</button>
                            <button type="submit" name="acao" value="negar">Negar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="top-buttons">
    <a href="cadastros.php">Voltar</a>
    </div>
</body>
</html>

<?php
$conexao->close();
?>
