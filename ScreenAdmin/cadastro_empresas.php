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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../ScreenUser/styleIndicar.php">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indicar</title>
</head>
<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="logo">
    </header>
    
    <nav class="menu-lateral">
        <div class="btn-expandir">
            <i class="bi bi-list"></i>
        </div>
        <ul>
            <li class="item-menu">
                <a href="index.php">
                    <span class="icon"><i class="bi bi-person-fill"></i></span>
                    <span class="txt-link">Perfil</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="../ScreenUser/minhas_indicacoes.php">
                    <span class="icon"><i class="bi bi-journal-plus"></i></span>
                    <span class="txt-link">Minhas Indicações</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="../ScreenUser/indicarUsuario.php">
                    <span class="icon"><i class="bi bi-plus-square"></i></span>
                    <span class="txt-link">Indicar</span>
                </a>
            </li>
            <li class="item-menu">
            <a href="../ScreenUser/solicitar_resgate.php">
                    <span class="icon"><i class="bi bi-coin"></i></span>
                    <span class="txt-link">Solicitar Resgate</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="../ScreenUser/logout.php">
                    <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="txt-link">Sair</span>
                </a>
            </li>
        </ul>
    </nav>
    <section>
        <div class="primeira_sessao">
         <div class="titulo">
            <h1>Cadastrar Empresa</h1>
            </div>
            <div class="form-indicacao">
        <form action="processar_indicacao.php" method="POST">
        <p>
            <label for="nome_empresa">Nome da Empresa:</label>
            <input type="text" id="nome_empresa" name="nome_empresa" required>
        </p>
        <p>
            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj" oninput="formatarCNPJ(this); buscarCNPJ(this.value)">
        </p>
        <p>
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required>
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </p>
        <p>
            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" oninput="formatarCEP(this); buscarCEP(this.value)" required>
        </p>
        <p>
            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" required>
        </p>
        <input type="submit" value="Cadastrar Empresa">
        <div class="search-container">
            <a href="cadastros.php">Voltar</a>
        </div>
            </form>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="<?php echo $_SESSION['message']['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </div>
    </section>
        </form>

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
