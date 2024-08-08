<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.html");
    exit();
}

$usuario = $_SESSION['usuario'];

include('../ScreenCadastro/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    $sql = "SELECT senha FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuario['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario_db = $result->fetch_assoc();

    if (password_verify($senha_atual, $usuario_db['senha'])) {
        if ($nova_senha === $confirmar_senha) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET senha = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("si", $nova_senha_hash, $usuario['id']);
            if ($stmt->execute()) {
                $msg = "Senha atualizada com sucesso!";
            } else {
                $msg = "Erro ao atualizar a senha.";
            }
        } else {
            $msg = "A nova senha e a confirmação não coincidem.";
        }
    } else {
        $msg = "Senha atual incorreta.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="shortcut icon" href="img/icon_uu.webp" type="image/x-icon">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/af6c14a78e.js" crossorigin="anonymous"></script>
    <title>Redefinir Senha</title>
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
            <h1>Redefinir Senha</h1>
            <hr>
        </article>
        <div class="container">
            <section>
                <a href="index.php" id="btn-minhaConta">
                    <i class="fi fi-sr-user"></i>
                    <p>Minha Conta</p>
                </a>
                <a href="redefinir_senha.php" class="active">
                    <i class="fi fi-sr-lock"></i>
                    <p>Redefinir Senha</p>
                </a>
                <a href="#">
                    <i class="fi fi-ss-book"></i>
                    <p>Minhas Indicações</p>
                </a>
                <a href="#">
                    <i class="fi fi-sr-square-plus"></i>
                    <p>Indicar</p>
                </a>
                <a href="logout.php" id="btn-sair">
                    <i class="fi fi-br-exit"></i>
                    <p>Sair</p>
                </a>
            </section>
            <div class="linha-vertical"></div>
            <article>
            <h2><strong>Usuário | <?php echo isset($usuario['nome']) ? htmlspecialchars($usuario['nome']) : ''; ?></strong></h2>
                            <div class="linha-horizontal"></div>

                <h2>Atualizar Senha</h2>
                <main class="dados-perfil">
                    <form method="post" action="">
                    <p>
                            <strong>CPF:</strong>
                            <input type="text" class="input-text" style="width: 97px;" value="<?php echo isset($usuario['cpf']) ? htmlspecialchars($usuario['cpf']) : ''; ?>" readonly>
                        </p>
                        <br>
                        <p> 
                            <strong>Email:</strong>
                            <input type="text" class="input-text" style="width: 235px;" value="<?php echo isset($usuario['email']) ? htmlspecialchars($usuario['email']) : ''; ?>" readonly>
                        </p>
                        <br>
                        <p>
                            <strong>Senha Atual:</strong>
                            <input type="password" name="senha_atual" required>
                        </p>
                        <br>
                        <p>
                            <strong>Nova Senha:</strong>
                            <input type="password" name="nova_senha" required>
                        </p>
                        <br>
                        <p>
                            <strong>Confirmar Nova Senha:</strong>
                            <input type="password" name="confirmar_senha" required>
                        </p>
                        <br>
                        <div class="btn-atualizar">
                        <button type="submit" style="text-align: center;" class="btn-atualizar">Atualizar Senha</button>
                        </div>
                        <?php if (isset($msg)) { ?>
                        <p class="feedback <?php echo $msg === "Senha atualizada com sucesso!" ? 'sucesso' : 'erro'; ?>">
                        <?php echo htmlspecialchars($msg); ?>
                        </p>
                        <?php } ?>

                    </form>
                    <div class="image">
                            <i class="fa-solid fa-circle-user" style="color:#304d30;"></i>
                            <a href="alterar_foto.php">Alterar foto do perfil</a>
                        </div>
                </main>
            </article>
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
