<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ou Ativar/Inativar Cadastros</title>
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
    <h1>Lista de Usuários</h1>
    <div class="search-container">
        <form action="cadastros.php" method="GET">
            <input type="text" id="pesquisar" name="search" placeholder="Pesquisar Usuário">
            <button type="submit">Pesquisar</button>
            <a href="index.php">Voltar</a>
        </form>
    </div>

    <div id="dados-pessoais" class="section">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Tipo de Conta</th>
                    <th>Data de Inativação</th>
                    <th class="actions-column">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include_once('../ScreenCadastro/config.php');
                    $filtro_pesquisa = "";
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $pesquisa = $_GET['search'];
                        $sql = "SELECT * FROM usuarios WHERE nome LIKE ?";
                        $stmt = $conexao->prepare($sql);
                        $pesquisa = "%$pesquisa%";
                        $stmt->bind_param("s", $pesquisa);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $sql = "SELECT * FROM usuarios";
                        $result = $conexao->query($sql);
                    }

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
                            echo "<td class='actions-column'>
                            <a href='#' onclick=\"showActivateModal(".$usuarios['id'].")\">Ativar</a>
                            </td>";
                        } else {
                            echo "<td class='actions-column'>
                            <a href='#' onclick=\"showInactivateModal(".$usuarios['id'].")\">Inativar</a>
                            <a href='edit.php?id=".$usuarios['id']."'>Editar</a>
                            </td>";
                        }
                        echo "</tr>";
                    }
                ?>               
            </tbody>
        </table>
    </div>

    <div class="top-buttons">
        <a href="cadastro_empresas.php">Cadastrar Empresa</a>
        <a href="cadastro_servico.php">Cadastrar Serviços</a>
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
    </script>
</body>
</html>