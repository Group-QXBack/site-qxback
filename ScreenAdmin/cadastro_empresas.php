<?php
session_start();

    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenLogin/index.html");
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
    background-color: #4a4a4a;
    color: #fff;
    text-align: center;
    margin: 0;
    padding: 0;
}

h1 {
    margin: 20px 0;
}

.container {
    width: 80%;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 8px;
}

.search-container, .section-buttons {
    margin-bottom: 20px;
}

.search-container a {
    display: inline-block;
    padding: 10px 20px;
    background: rgba(0, 0, 0, 0.3);
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.search-container a:hover {
    background: #4bc866;
}

.section-buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.section-buttons form {
    width: 100%;
}

form label {
    display: block;
    margin: 10px 0 5px;
}

form input[type="text"], form input[type="email"] {
    width: calc(100% - 22px);
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 10px;
}

form input[type="submit"] {
    width: 100%;
    padding: 10px;
    background: rgba(0, 0, 0, 0.3);
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

form input[type="submit"]:hover {
    background: #4bc866;
}

.success-message, .error-message {
    padding: 10px;
    margin: 20px 0;
    border-radius: 4px;
    color: #fff;
}

.success-message {
    background: #4caf50;
}

.error-message {
    background: #f44336;
}

table {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: rgba(0, 0, 0, 0.3);
}

tr:hover {
    background: rgba(255, 255, 255, 0.1);
}

.actions-column a {
    display: inline-block;
    padding: 8px 12px;
    background: rgba(0, 0, 0, 0.3);
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.actions-column a:hover {
    background: #149a50;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Empresas</h1>

        <div class="section-buttons">
            <form method="POST" action="cadastro_empresas.php">
                <label for="cnpj">CNPJ:</label>
                <input type="text" id="cnpj" name="cnpj" oninput="formatarCNPJ(this); buscarCNPJ(this.value)">

                <label for="nome_empresa">Nome da Empresa:</label>
                <input type="text" id="nome_empresa" name="nome_empresa" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" oninput="formatarCEP(this); buscarCEP(this.value)" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" required>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" required>

                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" maxlength="2" required>

                <input type="submit" value="Cadastrar Empresa">
            </form>
        </div>
        <div class="search-container">
            <a href="cadastros.php">Voltar</a>
        </div>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="<?php echo $_SESSION['message']['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </div>

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