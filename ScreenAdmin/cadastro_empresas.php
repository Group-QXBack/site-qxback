<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.html");
    exit();
}

include '../ScreenCadastro/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cnpj = $_POST['cnpj'];
    $nome_empresa = $_POST['nome_empresa'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];

    // Verificar se o CNPJ já está cadastrado
    $sql = "SELECT id FROM empresas WHERE cnpj = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $cnpj);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'CNPJ já cadastrado.'];
        $stmt->close();
        header("Location: cadastro_empresas.php");
        exit();
    }
    $stmt->close();

    // Inserir a nova empresa
    $sql = "INSERT INTO empresas (cnpj, nome_empresa, telefone, email, endereco, cidade, estado, cep) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssssss", $cnpj, $nome_empresa, $telefone, $email, $endereco, $cidade, $estado, $cep);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Empresa cadastrada com sucesso!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro ao cadastrar empresa.'];
    }
    $stmt->close();
    header("Location: cadastro_empresas.php");
    exit();
}

// Consultar empresas
$sql = "SELECT * FROM empresas";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empresas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
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
    <h1>Cadastro de Empresas</h1>
    <div class="search-container">
        <a href="cadastros.php">Voltar</a>
    </div>

    <div class="section-buttons">
        <form method="POST" action="cadastro_empresas.php">
            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj"  oninput="formatarCNPJ(this); buscarCNPJ(this.value)"><br><br>

            <label for="nome_empresa">Nome da Empresa:</label>
            <input type="text" id="nome_empresa" name="nome_empresa" required><br><br>
            
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" oninput="formatarCEP(this); buscarCEP(this.value)" required><br><br>
            
            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" required><br><br>
            
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" required><br><br>
            
            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" maxlength="2" required><br><br>
            
            <input type="submit" value="Cadastrar Empresa">
        </form>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="<?php echo $_SESSION['message']['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
            <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <script>
        function formatarCNPJ(campo) {
            campo.value = campo.value.replace(/\D/g, '');
            campo.value = campo.value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
        }

        function formatarCEP(campo) {
            campo.value = campo.value.replace(/\D/g, '');
            campo.value = campo.value.replace(/^(\d{5})(\d{3})/, "$1-$2");
        }

        function buscarCNPJ(cnpj) {
            cnpj = cnpj.replace(/[^\d]/g, '');

            if (cnpj.length === 14) {
                fetch(`buscar_cnpj.php?cnpj=${cnpj}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "OK") {
                            document.getElementById('nome_empresa').value = data.nome;
                        } else {
                            alert("CNPJ não encontrado.");
                        }
                    })
                    .catch(error => {
                        console.error("Erro ao buscar CNPJ:", error);
                        alert("Erro ao buscar CNPJ.");
                    });
            }
        }

        function buscarCEP(cep) {
            cep = cep.replace(/[^\d]/g, '');

            if (cep.length === 8) {
                fetch(`buscar_cep.php?cep=${cep}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.erro) {
                            alert("CEP não encontrado.");
                        } else {
                            document.getElementById('endereco').value = data.logradouro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('estado').value = data.uf;
                        }
                    })
                    .catch(error => {
                        console.error("Erro ao buscar CEP:", error);
                        alert("Erro ao buscar CEP.");
                    });
            }
        }
    </script>
</body>
</html>
