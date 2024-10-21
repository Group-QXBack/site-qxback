<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenLogin/index.html");
    exit();
}

$usuario = $_SESSION['usuario'];

include_once('../ScreenCadastro/config.php');

$registros_por_pagina = 10;
$página_atual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($página_atual - 1) * $registros_por_pagina;

$tipo_cadastro = isset($_GET['tipo_cadastro']) ? $_GET['tipo_cadastro'] : 'usuarios';
$filtro_pesquisa = isset($_GET['search']) ? $_GET['search'] : '';
$filtro_param = "%{$filtro_pesquisa}%";

if ($tipo_cadastro == 'usuarios') {
    $sql_count = "SELECT COUNT(*) AS total FROM usuarios WHERE nome LIKE ? OR cpf LIKE ?";
    $stmt_count = $conexao->prepare($sql_count);
    $stmt_count->bind_param('ss', $filtro_param, $filtro_param);
    $stmt_count->execute();
    $total_result = $stmt_count->get_result();
    $total_registros = $total_result->fetch_assoc()['total'];
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    $sql = "SELECT * FROM usuarios WHERE nome LIKE ? OR cpf LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssii", $filtro_param, $filtro_param, $registros_por_pagina, $offset);
} elseif ($tipo_cadastro == 'empresas') {
    $sql_count = "SELECT COUNT(*) AS total FROM empresas WHERE nome_empresa LIKE ?";
    $stmt_count = $conexao->prepare($sql_count);
    $stmt_count->bind_param('s', $filtro_param);
    $stmt_count->execute();
    $total_result = $stmt_count->get_result();
    $total_registros = $total_result->fetch_assoc()['total'];
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    $sql = "SELECT * FROM empresas WHERE nome_empresa LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sii", $filtro_param, $registros_por_pagina, $offset);
} elseif ($tipo_cadastro == 'servicos') {
    $sql_count = "
        SELECT COUNT(*) AS total
        FROM servicos s
        LEFT JOIN empresa_servico es ON s.id = es.servico_id
        LEFT JOIN empresas e ON es.empresa_id = e.id
        WHERE s.nome LIKE ? OR e.nome_empresa LIKE ?";
    
    $stmt_count = $conexao->prepare($sql_count);
    $stmt_count->bind_param('ss', $filtro_param, $filtro_param);
    $stmt_count->execute();
    $total_result = $stmt_count->get_result();
    $total_registros = $total_result->fetch_assoc()['total'];
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    $sql = "
        SELECT s.id, s.nome, s.descricao, COALESCE(e.nome_empresa, 'Não Associada') AS nome_empresa
        FROM servicos s
        LEFT JOIN empresa_servico es ON s.id = es.servico_id
        LEFT JOIN empresas e ON es.empresa_id = e.id
        WHERE s.nome LIKE ? OR e.nome_empresa LIKE ?
        GROUP BY s.id, s.nome, s.descricao, e.nome_empresa
        ORDER BY nome_empresa
        LIMIT ? OFFSET ?";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssii", $filtro_param, $filtro_param, $registros_por_pagina, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ou Ativar/Inativar Cadastros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #42FF00;
        }

        header .logo {
            display: flex;
            margin-left: 30px;
            height: 45px;
            margin-top: 2px;
        }
        header .icon{
            height: 20px;
            margin: 10px;
        }

        .menu-icon {
            cursor: pointer;
            display: flex;
            justify-content: end;
        }

        .nav-menu {
            list-style: none;
            display: none; /* Oculto inicialmente */
            position: absolute; /* Torna o menu flutuante */
            top: 60px; /* Distância da barra de navegação */
            right: 20px; /* Alinha à direita */
            padding: 10px;
            border-radius: 5px;
        }

        .nav-menu.show {
            display: block; /* Exibe quando ativado */
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            text-decoration: none;
            color: white;
            font-size: 16px;
            display: block;
            padding: 10px 15px;
            transition: background 0.3s ease;
        }

        .nav-link:hover {
            background-color: #555;
            border-radius: 5px;
        }

        h1 {
            margin: 20px 0;
            font-size: 2em;
        }
        .search-container {
            display: flex;
            justify-content: center;
            
        }
        .search-container form {
            display: flex;
            align-items: center;
            max-width: 1000px;
            width: 100%;
        }
        .search-container input[type="text"] {
            margin-top: 8px;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
        }
        .search-container button, .search-container a {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            text-decoration: none;
            margin: 10px;
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

            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 10px;
            height: 35px;
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
        .pagination {
            margin: 20px 0;
            display: flex;
            justify-content: center;
        }

        .pagination a {
            text-decoration: none;
            color: whitesmoke;
            background-color: rgb(0, 0, 0, 0.3);
            padding: 10px 20px;
            border-radius: 4px;
            margin: 0 5px;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 16px;
        }

        .pagination a:hover {
            background-color: rgb(75, 198, 133);
            transform: scale(1.05);
        }

        .pagination a.disabled {
            background-color: rgba(0, 0, 0, 0.1);
            cursor: not-allowed;
            color: #666;
        }

    </style>
</head>
<body>
<header>
        <img src="../imagens/logobranca1.png" class="logo" alt="logo">

        <div class="menu-icon">
            <button onclick="menuShow()" style="background-color: #1d1d1d; border: none;">
                <img class="icon" src="../imagens/menubar.png" alt="Menu">
            </button>
        </div>

        <ul class="nav-menu" id="menu">
            <li class="nav-item"><a href="./cadastro_empresas.php" class="nav-link">Cadastrar Empresa</a></li>
            <li class="nav-item"><a href="./cadastro_servico.php" class="nav-link">Cadastrar Serviço</a></li>
            <li class="nav-item"><a href="./solicitacoes_reativacao.php" class="nav-link">Reativação</a></li>
        </ul>
</header>

    <h1 style="color: #42FF00">Lista de Cadastros</h1>
    <div class="search-container">
    <form action="cadastros.php" method="GET">
        <input type="text" id="pesquisar" name="search" placeholder="Pesquisar" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Pesquisar</button>
        <a href="index.php">Voltar</a>
        <input type="hidden" name="tipo_cadastro" value="<?php echo htmlspecialchars($tipo_cadastro); ?>">
    </form>
</div>

    <div class="search-container">
        <form action="cadastros.php" method="GET">
            <select name="tipo_cadastro" id="tipo_cadastro" onchange="this.form.submit()">
                <option value="usuarios" <?php if(isset($_GET['tipo_cadastro']) && $_GET['tipo_cadastro'] == 'usuarios') echo 'selected'; ?>>Usuários</option>
                <option value="empresas" <?php if(isset($_GET['tipo_cadastro']) && $_GET['tipo_cadastro'] == 'empresas') echo 'selected'; ?>>Empresas</option>
                <option value="servicos" <?php if(isset($_GET['tipo_cadastro']) && $_GET['tipo_cadastro'] == 'servicos') echo 'selected'; ?>>Serviços</option>
            </select>
        </form>
    </div>

    <div id="dados-pessoais" class="section">
    <?php
        if ($tipo_cadastro == 'usuarios') {
            echo '<h2 style="color: #42FF00">Lista de Usuários</h2>';
            echo '<table>';
            echo '<thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>Tipo de Conta</th><th>Data de Inativação</th><th class="actions-column">Ações</th></tr></thead><tbody>';
            while ($usuarios = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$usuarios['id']."</td>";
                echo "<td>".$usuarios['nome']."</td>";
                echo "<td>".$usuarios['email']."</td>";
                echo "<td>".$usuarios['telefone']."</td>";
                echo "<td>".$usuarios['tipo_conta']."</td>";
                $dataInativacao = !empty($usuarios['data_inativacao']) ? date('d/m/Y', strtotime($usuarios['data_inativacao'])) : 'N/A';
                echo "<td>".$dataInativacao."</td>";
                if ($usuarios['tipo_conta'] == 'inativo') {
                    echo "<td class='actions-column'><a href='#' onclick=\"showActivateModal(".$usuarios['id'].")\">Ativar</a></td>";
                } else {
                    echo "<td class='actions-column'><a href='#' onclick=\"showInactivateModal(".$usuarios['id'].")\">Inativar</a><a href='edit.php?id=".$usuarios['id']."'>Editar</a></td>";
                }
                echo "</tr>";
            }
            echo '</tbody></table>';
        } elseif ($tipo_cadastro == 'empresas') {
            echo '<h2>Lista de Empresas</h2>';
            echo '<table>';
            echo '<thead><tr><th>ID</th><th>Nome</th><th>CNPJ</th><th>Telefone</th><th>Email</th><th>Endereço</th><th class="actions-column">Ações</th></tr></thead><tbody>';
            while ($empresa = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$empresa['id']."</td>";
                echo "<td>".$empresa['nome_empresa']."</td>";
                echo "<td>".$empresa['cnpj']."</td>";
                echo "<td>".$empresa['telefone']."</td>";
                echo "<td>".$empresa['email']."</td>";
                echo "<td>".$empresa['endereco']."</td>";
                echo "<td class='actions-column'><a href='edit_empresa.php?id=".$empresa['id']."'>Editar</a></td>";
                echo "</tr>";
            }
            echo '</tbody></table>';
        } elseif ($tipo_cadastro == 'servicos') {
            echo '<h2>Lista de Serviços</h2>';
            echo '<table>';
            echo '<thead><tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Empresa</th><th class="actions-column">Ações</th></tr></thead><tbody>';
            while ($servico = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$servico['id']."</td>";
                echo "<td>".$servico['nome']."</td>";
                echo "<td>".$servico['descricao']."</td>";
                echo "<td>".$servico['nome_empresa']."</td>";
                echo "<td class='actions-column'><a href='edit_servico.php?id=".$servico['id']."'>Editar</a></td>";
                echo "</tr>";
            }
            echo '</tbody></table>';
        }
        ?>
        <div class="pagination">
            <?php if ($página_atual > 1): ?>
                <a href="?tipo_cadastro=<?php echo urlencode($tipo_cadastro); ?>&page=<?php echo $página_atual - 1; ?>">&laquo; Anterior</a>
            <?php else: ?>
                <a href="#" class="disabled">&laquo; Anterior</a>
            <?php endif; ?>

            <?php if ($página_atual < $total_paginas): ?>
                <a href="?tipo_cadastro=<?php echo urlencode($tipo_cadastro); ?>&page=<?php echo $página_atual + 1; ?>">Próximo &raquo;</a>
            <?php else: ?>
                <a href="#" class="disabled">Próximo &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    </div>
                


    <div id="inactivateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('inactivateModal')">&times;</span>
            <h2>Inativar Usuário</h2>
            <form action="inativar_user.php" method="post">
                <input type="hidden" id="inactivateUserId" name="userId" value="">
                <textarea name="motivo" placeholder="Digite o motivo da inativação" required></textarea>
                <input type="submit" value="Confirmar Inativação">
            </form>
        </div>
    </div>

    <div id="activateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('activateModal')">&times;</span>
            <h2>Ativar Usuário</h2>
            <form action="ativar_user.php" method="post">
                <input type="hidden" id="activateUserId" name="userId" value="">
                <input type="submit" value="Confirmar Ativação">
            </form>
        </div>
    </div>

    <script>
        function showInactivateModal(userId) {
            document.getElementById('inactivateUserId').value = userId;
            document.getElementById('inactivateModal').style.display = 'block';
        }

        function showActivateModal(userId) {
            document.getElementById('activateUserId').value = userId;
            document.getElementById('activateModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        }
        function menuShow() {
            const menu = document.getElementById('menu');
             menu.classList.toggle('show');
        }

    </script>
</body>
</html>