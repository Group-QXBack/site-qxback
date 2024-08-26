<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.php");
    exit();
}
$usuario = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../ScreenCadastro/config.php';

    $telefone = $_POST['telefone'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $numero = $_POST['numero'] ?? ''; 
    $complemento = $_POST['complemento'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';

    $stmt = $conexao->prepare("UPDATE usuarios SET telefone=?, cep=?, endereco=?, numero=?, complemento=?, bairro=?, cidade=?, estado=? WHERE cpf=?");
    $stmt->bind_param('sssssssss', $telefone, $cep, $endereco, $numero, $complemento, $bairro, $cidade, $estado, $usuario['cpf']);

    if ($stmt->execute()) {
        $usuario['telefone'] = $telefone;
        $usuario['cep'] = $cep;
        $usuario['endereco'] = $endereco;
        $usuario['numero'] = $numero; 
        $usuario['complemento'] = $complemento;
        $usuario['bairro'] = $bairro;
        $usuario['cidade'] = $cidade;
        $usuario['estado'] = $estado;

        $_SESSION['message'] = ['type' => 'success', 'text' => 'Dados atualizados com sucesso!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Ocorreu um erro ao atualizar seus dados. Tente novamente.'];
    }

    header("Location: ../ScreenUser/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style1.css">
    <link rel="shortcut icon" href="img/icon_uu.webp" type="image/x-icon">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/af6c14a78e.js" crossorigin="anonymous"></script>
    <title>Completar Cadastro</title>
    <style>
        body {
            font-family: 'Red Hat Display', sans-serif;
            background-color: #000;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        header {
            margin-bottom: 20px;
        }
        .container {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        h1 {
            color: white;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            color: white;
            display: block;
            margin-bottom: 5px;
        }
        .input-text {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .btn-salvar {
            text-align: center;
        }
        .btn-finalizar-cadastro {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }
        .success-message, .error-message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            color: #fff;
        }
        .success-message {
            background-color: #28a745;
        }
        .error-message {
            background-color: #dc3545;
        }
        footer {
            margin-top: 20px;
            text-align: center;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="Logo da página">
    </header>

    <div class="container">
        <article>
            <h1>Completar Cadastro</h1>
            <hr>
        </article>
        <form action="completar_cadastro.php" method="post" class="form-completar">
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" class="input-text" oninput="formatarTelefone(this)" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" oninput="formatarCEP(this)" class="input-text" value="<?php echo htmlspecialchars($usuario['cep'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" class="input-text" value="<?php echo htmlspecialchars($usuario['estado'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" class="input-text" value="<?php echo htmlspecialchars($usuario['cidade'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" class="input-text" value="<?php echo htmlspecialchars($usuario['bairro'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" class="input-text" value="<?php echo htmlspecialchars($usuario['endereco'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" class="input-text" value="<?php echo htmlspecialchars($usuario['numero'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="complemento">Complemento:</label>
                <input type="text" id="complemento" name="complemento" class="input-text" value="<?php echo htmlspecialchars($usuario['complemento'] ?? ''); ?>">
            </div>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="<?php echo $_SESSION['message']['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
                    <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
                </div>
            <?php endif; ?>
            <div class="btn-salvar">
                <button><a href="index.php" class="btn-cadastro">Voltar</a></button>
            </div>
            <div class="btn-salvar">
                <button type="submit" class="btn-cadastro">Finalizar Cadastro</button>
            </div>
        </form>
    </div>
    <footer class="primeiro-rodape">
        <div class="voltar-ao-topo">
            <hr>
            <i class="fa-solid fa-arrow-up-long" style="color: #00ff00;"></i>
            <a style="cursor: pointer;" onclick="subiraoTopo();">Voltar ao topo</a>
            <hr>
        </div>
        <article>
            <div class="container-texto">
                <div class="primeiro-txt">
                    <h3>
                        <strong style="cursor: default; color: rgb(255, 255, 255);">QXBack</strong>
                    </h3>
                    <p>
                        <a href="#">Programa</a>
                        <a href="#">Como indicar?</a>
                        <a href="#">Portal de indicações</a>
                    </p>
                </div>
                <div class="segundo-txt">
                    <h3>
                        <strong style="cursor: default; color: rgb(255, 255, 255);">Serviços</strong>
                    </h3>
                    <p>
                        <a href="#">Atendimento Virtual</a>
                        <a href="#">Feedback</a>
                    </p>
                </div>
            </div>
        </article>
    </footer>
    <footer class="segundo-rodape">
        <div class="copyright">
            <nav class="icons">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-square-instagram"></i></a>
            </nav>
            <p>Política de privacidade | Termo de uso | Cookies</p>
            <p>&copy; 2024 | 3Point</p>
        </div>
    </footer>
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cepInput = document.getElementById('cep');
            cepInput.addEventListener('blur', function() {
                const cep = this.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById('estado').value = data.uf;
                                document.getElementById('cidade').value = data.localidade;
                                document.getElementById('bairro').value = data.bairro;
                                document.getElementById('endereco').value = data.logradouro;
                            }
                        });
                }
            });
        });

        function formatarTelefone(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            if (value.length > 6) {
                input.value = value.replace(/(\d{2})(\d{5})(\d{4})/, '$1 $2-$3');
            } else if (value.length > 2) {
                input.value = value.replace(/(\d{2})(\d+)/, '$1 $2');
            } else {
                input.value = value;
            }
        }

        function formatarCEP(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            if (value.length > 5) {
                input.value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            } else {
                input.value = value;
            }
        }

        function subiraoTopo() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
