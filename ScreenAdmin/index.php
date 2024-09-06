<?php
    session_start();

    $message = $_SESSION['message'] ?? null;
    unset($_SESSION['message']); 

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../ScreenUser/index.html");
        exit();
    }
    include '../ScreenCadastro/config.php';

    $usuario = $_SESSION['usuario'];
    $userId = $usuario['id'];

    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $_SESSION['usuario'] = $usuario; 
    } else {
        $message = ['type' => 'error', 'text' => 'Usuário não encontrado.'];
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="img/icon_uu.webp" type="image/x-icon">
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-straight/css/uicons-solid-straight.css'>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
        <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/af6c14a78e.js" crossorigin="anonymous"></script>
        <title>Área do Usuário</title>
        <style>
            :root {
    --cinza: #E7E4D7;
    --preto: #000000;
    --meiobranco: #ececec;
    --verde: #42FF00;
    --branconeve: rgb(255, 255, 255);
    --verdefraco: #afc9afa8;
}

* {
    margin: 0;
    padding: 0;

}

body {
    width: 100%;
    background-image: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
    font-family: "Red Hat Display", sans-serif;
}

header {
    width: 100%;
    height: 80px;
    background-image: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #ffffff;
}

header>.logo {
    height: 45px;
    padding-left: 100px;
    margin-top: 10px;
}

header .perfil {
    width: 150px;
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding-right: 100px;

}

header .perfil i {
    font-size: 30px;
}

.corpo_principal {
    display: flex;
    flex-direction: column;
    padding: 60px;
    gap: 50px;
}
.select-area {
    display: flex;
    flex-direction: column;
}

.select-area select {
    height: auto;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
    box-sizing: border-box;
}

.corpo_principal>article {
    display: flex;
    align-items: center;
    gap: 30px;
    font-family: Arial, Helvetica, sans-serif;
}

.corpo_principal article h1 {
    font-size: 30px;
    color: #ffffff;
}

.corpo_principal article hr {
    border-color: #ffffff;
    flex: 1;
    margin: 10px;
}

.container {
    display: grid;
    height: 500px;
    place-items: center;
    grid-template-columns: 18% 12% 65%;
    grid-template-areas:
        "section linha-vertical article"
    ;
}

.container section {
    width: 200px;
    display: grid;
    grid-area: section;
}
.image {
    text-align: center; 
    margin: 20px auto; 
}

.foto-perfil {
    width: 100px; 
    height: 100px; 
    border-radius: 50%; 
    object-fit: cover; 
    border: 2px solid #ddd; 
    display: inline-block; 
    vertical-align: middle; 
}


.container section>a {
    width: 100%;
    height: 45px;
    text-decoration: none;
    color: #ffffff;
    display: flex;
    gap: 10px;
    line-height: 30px;
    padding: 9px;
    font-size: 20px;
}

.container section a:hover {
    background-color: var(--verde);
    border-radius: 14px;
    color: white;
    transition: all 0.2s ease;
}

#btn-sair:hover {
    color: white;
    border-radius: 14px;
    background-color: rgb(255, 0, 0);
    transition: all 0.2s ease;
}
#btn-minhaConta:hover {
    color: black;
}
.linha-vertical{
    grid-area: linha-vertical;
    height: 400px;
    border-left: 1px solid;
}
.container article {
    grid-area: article;
    padding: 30px;
    width: 100%;
    height: 450px;
    display: flex;
    flex-direction: column;
    gap: 30px;
    background-color: white;
    border-radius: 10px;
}
.linha-horizontal {
    position: relative;
    right: 30px;
    width: 106.5%;
    border-bottom: 1px solid;
    border-color: #1bff1b;
}
.input-text{
    border: none;
    height: 20px;
    width: 120px;
    background-color: var(--meiobranco);
}
.input-text::placeholder {
    opacity: 0.4;
}
.input-date {
    border: none;
}
.dados-perfil {
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
}

.dados-perfil>.dados {
    display: grid;
    gap: 10px;
}

.dados p {
    display: flex;
    align-items: center;
    height: 30px;
    gap: 5px;
}

.dados-perfil>.image {
    display: grid;
    place-items: center;
    padding-right: 70px;
}

.dados-perfil>.image>i {
    font-size: 100px;
}

.dados-perfil>.image>a {
    width: 120px;
    height: 45px;
    text-decoration: none;
    text-align: center;
    background-color: white;
    border-radius: 9px;
    border: 2px solid #304D30;
    font-weight: bold;
    color: #304D30;
}

.dados-perfil>.image>a:hover {
    background-color: var(--verde);
    color: white;
    transition: all 0.6s ease;
}

.container>article>.btn-salvar {
    display: grid;
    place-items: center;
    height: 80px;
}

.container>article>.btn-salvar>a {
    display: grid;
    place-items: center;
    text-decoration: none;
    border-radius: 8px;
    background-color: #a1d7a1;
    font-weight: bold;
    font-size: 15px;
    width: 200px;
    height: 40px;
}

.container>article>.btn-salvar>a:hover {
    background-color: #95eb95;
    transition:0.5s;

}

.btn-salvar input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-atualizar {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1rem;
}

.btn-atualizar button {
    border: none;
    background-color: #44ff00; 
    color: #000000; 
    padding: 0.6rem 3rem;
    border-radius: 5px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 700;
    transition: background-color 0.3s ease, transform 0.2s ease; 
}

.btn-atualizar button:hover {
    background-color: #3ad302; 
    transform: scale(1.05); 
}

.btn-atualizar button:focus {
    outline: none; 
}

.btn-salvar input[type="submit"]:hover {
    background-color: #45a049;
}


.feedback {
    font-size: 1rem;
    font-weight: bold;
    margin-top: 1rem;
}

.feedback.sucesso {
    color: #28a745; 
}

.feedback.erro {
    color: #dc3545; 
}

.submenu {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    background-color: #000000;
    font-weight: bold;
    height: 35px;
}

.submenu ul {
    display: flex;
    list-style: none;
    padding: 10px;
    gap: 500px;
}

.submenu ul li {
    position: relative;
    cursor: pointer;

}

.submenu ul li a,
.submenu ul li p {
    color: #ffffff;
    transition: background .3s, color .3s;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 5px;
}

.submenu ul li a:hover,
.submenu ul li p:hover {
    background-color: #6f886fa8;
    color: #000000;
}

.submenu ul ul {
    display: none;
    position: absolute;
    top: 100%;
    width: 220px;
    padding: 5px 10px;
    border-radius: 10px;
    background-color: #000000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.submenu ul li:hover ul {
    display: block;
}

.submenu ul ul li {
    margin: 0;
}

.submenu ul ul li a {
    display: block;
}

.primeiro-rodape {
    background-image: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
    width: 100%;
    height: 100%;
}

.voltar-ao-topo {
    display: flex;
    padding: 10px;
    gap: 12px;
}

.primeiro-rodape a {
    text-decoration: none;
    color: #ffffff;
}

.voltar-ao-topo hr {
    flex: 1;
    margin: 10px auto;
}


.voltar-ao-topo i {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.primeiro-rodape article {
    display: block;
    padding: 20px;
}

.container-texto {
    display: flex;
    gap: 50px;
    height: 2 00px;
    margin-left: 8%;
    margin-top: 5%;
}

.primeiro-txt,
.segundo-txt {
    height: 200px;
    width: 200px;
    display: flex;
    gap: 15px;
    flex-direction: column;
}

.primeiro-txt p,
.segundo-txt p {
    display: grid;
    gap: 5px;

}

.primeiro-txt p :hover,
.segundo-txt p :hover {
    color: #00d100;
    transition: 0.5s;
}


.dados-icons {
    color: var(--branconeve);
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.dados-icons>.icons {
    font-size: 28px;
    display: flex;
    gap: 12px;
}

.icons a i {
    color: var(--branconeve);
}

.icons a i:hover {
    transition: 0.5s;
    color: var(--branconeve);
}

.segundo-rodape {
    background-image: linear-gradient(to right, rgb(0, 0, 0), rgb(0, 209, 0));
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 60px;
}

.copyright p {
    margin: 1px 0;
    color: var(--branconeve);
}

@media screen and (max-width:1300px) {
    .container section>a {
        width: 129%;
        font-size: 10px;
    }

    .corpo_principal {
        padding: 20px;
    }

    .submenu ul {
        gap: 299px;
    }
}

@media screen and (max-width:490px) {
    .cbc-acessibilidade {
        display: none;
    }

    header>.perfil>strong {
        display: none;
    }

    header>.logo {
        width: 90px;
        height: 40px;
    }

    header .logo {
        padding-left: 50px;
    }

    header .perfil {
        padding-right: 0;
    }


    .container {
        display: flex;
        gap: 30px;
        flex-direction: column;
        grid-template-columns: auto auto;
        grid-template-areas:
            "section"
            "article";
        height: 100%;
    }
    .linha-vertical{display: none;}
    .linha-horizontal {
        right: 0;
        width: 100%;
    }

    .corpo_principal>article>h1 {
        font-size: 20px;
    }

    .container section {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 9px;
        width: 100%;

    }

    .container section a {
        padding: 0;
        gap: 6px;
        justify-content: center;
        width: 100px;
    }

    .container article {
        gap: 23px;
        font-size: 13px;
        height:600px;
        width: 80%;
    }

    .container article hr {
        flex: 0;
        margin: 0;
    }

    .container>article>strong {
        font-size: 13px;
    }
    .dados-perfil input[type="password"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .container>article>.dados-perfil {
        flex-wrap: wrap-reverse;
        justify-content: center;
        gap: 50px;
    }

    .container>article>.dados-perfil>.image {
        gap: 12px;
        padding-right: 0;
    }

    .dados-perfil a {
        display: grid;
        place-items: center;
    }

    .corpo_principal {
        padding: 20px;
    }

    .submenu ul {
        gap: 150px;
    }

    .submenu p,
    a {
        font-size: 12px;
    }

    #btn-suporte {
        right: 0;
    }

    footer {
        height: 100%;
        color: white;
    }

    .img-dados {
        display: block;
    }

    .img-logo {
        justify-content: end;
    }

    #logo-nupat {
        width: 200px;
        height: 200px;
    }

    #logo-ifrn {
        width: 150px;
        height: 150px;
    }

    .dados-icons {
        width: 90%;
    }

    .dados-icons strong,
    .dados-icons p {
        font-size: 17px;
    }

    .copyright p {
        font-size: 9px;
    }
    
}
        </style>
    </head>
    <body>
        <header>
            <img src="../imagens/logobranca1.png" class="logo" alt="Logo da página">
        </header>
        <div class="corpo_principal">
            <article>
                <h1>Meus Dados</h1>
                <hr>
            </article>
            <div class="container">
                <?php if ($message): ?>
                    <div class="<?php echo $message['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
                        <?php echo htmlspecialchars($message['text']); ?>
                    </div>
                <?php endif; ?>
                <section>
                    <a href="#" id="btn-minhaConta" style="border-radius: 14px;">
                        <i class="fi fi-sr-user"></i>
                        <p>Minha Conta</p>
                    </a>
                    <a href="redefinir_senha.php">
                        <i class="fi fi-sr-lock"></i>
                        <p>Redefinir Senha</p>
                    </a>
                    <a href="indicacoes.php">
                        <i class="fi fi-ss-book"></i>
                        <p>Indicações</p>
                    </a>
                    <a href="cadastros.php">
                        <i class="fi fi-sr-square-plus"></i>
                        <p>Cadastros</p>
                    </a>
                    <a href="../ScreenUser/logout.php" id="btn-sair">
                        <i class="fi fi-br-exit"></i>
                        <p>Sair</p>
                    </a>
                </section>
                <div class="linha-vertical"></div>
                <article>
                    <h2><strong>Administrador | <?php echo htmlspecialchars($usuario['nome'] ?? ''); ?></strong></h2>
                    <div class="linha-horizontal"></div>
                    <main class="dados-perfil">
                        <div class="dados">
                            <p>
                                <strong>CPF:</strong>
                                <input type="text" class="input-text" style="width: 97px;" value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>" readonly>
                            </p>
                            <p>
                                <strong>Data de Nascimento:</strong>
                                <input type="date" class="input-date" value="<?php echo htmlspecialchars($usuario['data_nasc'] ?? ''); ?>" readonly>
                            </p>
                            <p>
                                <strong>Email:</strong>
                                <input type="text" class="input-text" style="width: 235px;" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" readonly>
                            </p>
                            <p>
                                <strong>Telefone:</strong>
                                <input type="text" class="input-text" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" readonly>
                            </p>
                            <p>
                                <strong>CEP:</strong>
                                <input type="text" class="input-text" style="width: 65px;" value="<?php echo htmlspecialchars($usuario['cep'] ?? ''); ?>" readonly>
                            </p>
                            <p>
                                <strong>Endereço:</strong>
                                <input type="text" class="input-text" style="width: 180px;" value="<?php echo htmlspecialchars($usuario['endereco'] ?? ''); ?>" readonly>
                            </p>
                            <p>
                                <strong>Complemento:</strong>
                                <input type="text" class="input-text" value="<?php echo htmlspecialchars($usuario['complemento'] ?? ''); ?>" readonly>
                            </p>
                        </div>
                        <div class="image">
                            <i class="fa-solid fa-circle-user" style="color: #304d30;"></i>
                            <a href="alterar_foto.php">Alterar foto do perfil</a>
                        </div>
                    </main>
                    <div class="btn-salvar">
                        <a href="completar_cadastro.php" class="btn-finalizar-cadastro">Editar Cadastro</a>
                    </div>
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