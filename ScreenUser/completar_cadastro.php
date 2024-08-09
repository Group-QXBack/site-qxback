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
    $complemento = $_POST['complemento'] ?? '';

    $stmt = $conexao->prepare("UPDATE usuarios SET telefone=?, cep=?, endereco=?, complemento=? WHERE cpf=?");
    $stmt->bind_param('sssss', $telefone, $cep, $endereco, $complemento, $usuario['cpf']);

    if ($stmt->execute()) {
        $_SESSION['usuario']['telefone'] = $telefone;
        $_SESSION['usuario']['cep'] = $cep;
        $_SESSION['usuario']['endereco'] = $endereco;
        $_SESSION['usuario']['complemento'] = $complemento;

        header("Location: perfil_completo.php");
        exit();
    } else {
        $error_message = "Ocorreu um erro ao atualizar seus dados. Tente novamente.";
    }
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
</head>
<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="Logo da página">
    </header>
    <div class="submenu">
        <ul>
            <li>
                <p>Status Indicações <i class="fa-solid fa-chevron-down"></i></p>
                <ul>
                    <li><a href="#">Indicações Iniciadas</a></li>
                    <li><a href="#">Indicações em Andamento</a></li>
                    <li><a href="#">Indicações Concluídas</a></li>
                </ul>
            </li>
            <li>
                <p>Suporte <i class="fa-solid fa-chevron-down"></i></p>
                <ul id="btn-suporte">
                    <li><a href="atendimento_virtual.html">Atendimento Virtual</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="corpo_principal">
        <article>
            <h1>Completar Cadastro</h1>
            <hr>
        </article>
        <div class="container">
            <form action="completar_cadastro.php" method="post" class="form-completar">
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" class="input-text" value="<?php echo isset($usuario['telefone']) ? htmlspecialchars($usuario['telefone']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" class="input-text" value="<?php echo isset($usuario['cep']) ? htmlspecialchars($usuario['cep']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" class="input-text" value="<?php echo isset($usuario['endereco']) ? htmlspecialchars($usuario['endereco']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" name="complemento" class="input-text" value="<?php echo isset($usuario['complemento']) ? htmlspecialchars($usuario['complemento']) : ''; ?>">
                </div>
                <?php if (isset($error_message)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                <div class="btn-salvar">
                    <button type="submit" class="btn-finalizar-cadastro">Finalizar Cadastro</button>
                </div>
            </form>
        </div>
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
</body>
</html>
