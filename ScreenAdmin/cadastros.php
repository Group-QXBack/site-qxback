<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ou deletar cadastros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to right, rgb(10, 100, 150), rgb(20, 150, 80));
            color: whitesmoke;
            text-align: center;
            background-size: cover; 
            background-attachment: fixed; 
            margin: 0; 
            padding: 0; 
        }
        .section-buttons {
            justify-content: center;
            margin-bottom: 20px;
        }
        .section-buttons button {
            padding: 8px;
            border: none;
            border-radius: 4px;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .section-buttons button:hover {
            background-color: rgb(75, 198, 133);
        }
        table {
            margin: 0 auto;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: rgb(0, 0, 0, 0.3);
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
       .actions-column a {
            text-decoration: none;
            color: white;
            padding: 8px;
            background-color: rgb(0, 0, 0, 0.3);
            border-radius: 4px;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .actions-column a:hover {
            background-color: rgb(20, 150, 80);
        }
        
        input[type="submit"], a {
            padding: 8px;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            font-size: 14px;
        }
        input[type="submit"]:hover, a:hover {
            background-color: rgb(75, 198, 133);
        }
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            padding: 8px;
            border: none;
            border-radius: 4px 0 0 4px;
            margin-right: -4px;
            box-sizing: border-box;
        }
        .search-container button {
            padding: 8px 20px;
            border: none;
            border-radius: 0 4px 4px 0;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            cursor: pointer;
            transition: background-color 0.3s;
            box-sizing: border-box;
            font-size: 14px;
        }
        .search-container button:hover {
            background-color: rgb(75, 198, 133);
        }

        .top-buttons button {
            padding: 8px;
            border-radius: 4px;
            background-color: rgb(0, 0, 0, 0.3);
            color: whitesmoke;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .top-buttons button:hover {
            background-color: rgb(75, 198, 133);
        }
    </style>
</head>
<body>
    <h1>Lista de Usuarios</h1>
    <div class="search-container">
        <form action="cadastros.php" method="GET">
            <input type="text" id="pesquisar" name="search" placeholder="Pesquisar Usuario">
            <button type="submit">Pesquisar</button>
            <a href="index.php">Voltar</a>
        </form>
    </div>
    <div class="section-buttons">
        <button id="dados-pessoais-btn" onclick="showSection('dados-pessoais')">Dados Pessoais</button>
        <button id="enderecos-contato-btn" onclick="showSection('enderecos')">Endereços</button>
    </div>

    <!-- Seção de Dados Pessoais -->
    <div id="dados-pessoais" class="section">
        <table>
            <!-- Cabeçalho da tabela para dados pessoais -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Data de Nascimento</th>
                    <th>Gênero</th>
                    <th>Tipo de Conta</th>
                    <th class="actions-column">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include_once('../ScreenCadastro/config.php');
                    $filtro_pesquisa = "";
                    if(isset($_GET['search']) && !empty($_GET['search'])) {
                        $pesquisa = $_GET['search'];
                        $sql = "SELECT * FROM usuarios WHERE nomev LIKE ?";
                        $stmt = $conexao->prepare($sql);
                        $pesquisa = "%$pesquisa%";
                        $stmt->bind_param("s", $pesquisa);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $sql = "SELECT * FROM usuarios";
                        $result = $conexao->query($sql);
                    }

                    // Lista os alunos
                    while($usuarios = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$usuarios['id']."</td>";
                        echo "<td>".$usuarios['nome']."</td>";
                        echo "<td>".$usuarios['cpf']."</td>";
                        echo "<td>".$usuarios['email']."</td>";
                        echo "<td>".$usuarios['telefone']."</td>";
                        echo "<td>".$usuarios['data_nasc']."</td>";
                        echo "<td>".$usuarios['genero']."</td>";
                        echo "<td>".$usuarios['tipo_conta']."</td>";

                        // Adicione mais colunas com outros dados do usuarios, se necessário
                        echo "<td class='actions-column'>
                            <a href='#".$usuarios['id']."'>Editar</a>
                            <a href='".$usuarios['id']."' onclick=\"return confirm('Tem certeza que deseja excluir este registro?')\">Excluir</a></td>";
                        echo "</tr>";
                    }
                ?>               
            </tbody>
        </table>
    </div>
    <div id="enderecos" class="section" style="display: none;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Estado</th>
                    <th>Cidade</th>
                    <th>Bairro</th>
                    <th>Endereço</th>
                    <th>Numero</th>
                    <th>Complemento</th>
                    <th class="actions-column">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include_once('../ScreenCadastro/config.php');

                    $filtro_pesquisa = "";

                    if(isset($_GET['search']) && !empty($_GET['search'])) {
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

                    // Lista os alunos
                    while($usuarios = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$usuarios['id']."</td>";
                        echo "<td>".$usuarios['nome']."</td>";
                        echo "<td>".$usuarios['estado']."</td>";
                        echo "<td>".$usuarios['cidade']."</td>";
                        echo "<td>".$usuarios['bairro']."</td>";
                        echo "<td>".$usuarios['endereco']."</td>";
                        echo "<td>".$usuarios['numero']."</td>";
                        echo "<td>".$usuarios['complemento']."</td>";    

                        echo "<td class='actions-column'>
                        <a href='edit.php?id=".$usuarios['id']."'>Editar</a>
                        <a href='delete.php?id=".$usuarios['id']."' onclick=\"return confirm('Tem certeza que deseja excluir este registro?')\">Excluir</a></td>";
                        echo "</tr>";
                    }
                ?>               
            </tbody>
        </table>
    </div>
  
    <script>
        function showSection(sectionId) {
            // Oculta todas as seções
            var sections = document.getElementsByClassName("section");
            for (var i = 0; i < sections.length; i++) {
                sections[i].style.display = "none";
            }
            // Mostra apenas a seção clicada
            document.getElementById(sectionId).style.display = "block";
        }
    </script>
</body>
</html>