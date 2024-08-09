<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.html");
    exit();
}
$usuario = $_SESSION['usuario'];
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
    <title>Indicar</title>
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
            <h1>Indicar</h1>
            <hr>
        </article>
        <div class="container">
            <section class="menu-lateral">
                <a href="index.php" id="btn-minhaConta">
                    <i class="fi fi-sr-user"></i>
                    <p>Minha Conta</p>
                </a>
                <a href="redefinir_senha.php">
                    <i class="fi fi-sr-lock"></i>
                    <p>Redefinir Senha</p>
                </a>
                <a href="#">
                    <i class="fi fi-ss-book"></i>
                    <p>Minhas Indicações</p>
                </a>
                <a href="indicar.php">
                    <i class="fi fi-sr-square-plus"></i>
                    <p>Indicar</p>
                </a>
                <a href="logout.php" id="btn-sair">
                    <i class="fi fi-br-exit"></i>
                    <p>Sair</p>
                </a>
            </section>
            <article class="form-container">
                <h2><strong>Indicação de Empresa</strong></h2>
                <main class="form-indicacao">
                <form action="processar_indicacao.php" method="POST">
                <p>
                <strong>Nome :</strong>
                <input type="text" class="input-text" name="nome_empresa" required>
                </p>
                <p>
            <strong>CPF :</strong>
            <input type="text" class="input-text" name="cpf" required>
        </p>
        <p>
        <strong>Telefone:</strong>
        <input type="text" class="input-text" name="telefone_empresa" required>
        </p>
        <p>
        <strong>Email:</strong>
        <input type="email" class="input-text" name="email_empresa" required>
        </p>
        <div class="btn-salvar">
        <button type="submit">Enviar Indicação</button>
    </div>
</form>

                </main>
            </article>
        </div>
    </div>
    <footer>
        <div class="voltar-ao-topo">
            <i class="fa-solid fa-arrow-up-long"></i>
            <a style="cursor: pointer;" onclick="subiraoTopo();">Voltar ao topo</a>
        </div>
        <div class="container-texto">
            <div class="primeiro-txt">
                <h3>QXBack</h3>
                <p>
                    <a href="#">Programa</a>
                    <a href="#">Como indicar?</a>
                    <a href="#">Portal de indicações</a>
                </p>
            </div>
            <div class="segundo-txt">
                <h3>Serviços</h3>
                <p>
                    <a href="#">Atendimento Virtual</a>
                    <a href="#">Feedback</a>
                </p>
            </div>
        </div>
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
