<?php
session_start();
include_once('../ScreenCadastro/config.php'); 

if (!isset($_SESSION['usuario'])) {
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
    <link rel="stylesheet" href="./indicacoes.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Indicações</title>
</head>
<body>
    <nav>
<div class="nav" id="nav">
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
    </nav>
        <h1>Minhas Indicações</h1>
    <div class="container">
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
        echo "<details>
            <summary>
                <span>{$row['nome_empresa']} - {$row['cnpj']} - {$row['status']}</span>
            </summary>
            <div>
                <p><strong>Serviços Indicados:</strong> {$row['servicos']}</p>
                <p><strong>Valor Pendente:</strong> R$ {$row['valor_pendente']}</p>
                <p><strong>Data da Indicação:</strong> {$row['data_indicacao']}</p>
            </div>
        </details>";
    }
} else {
    echo "<p>Nenhuma indicação encontrada.</p>";
}
?>

    </div>
    <footer>
        &copy; 2024 QXBack. Todos os direitos reservados.
    </footer>
</body>
</html>
