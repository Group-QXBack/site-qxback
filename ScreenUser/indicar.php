<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'user') {
    header("Location: ../ScreenUser/index.html");
    exit();
}
$usuario = $_SESSION['usuario'];

$mensagem = '';
if (isset($_GET['success'])) {
    $mensagem = '<div class="alert success">' . htmlspecialchars($_GET['success']) . '</div>';
} elseif (isset($_GET['error'])) {
    $mensagem = '<div class="alert error">' . htmlspecialchars($_GET['error']) . '</div>';
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
    <title>Indicar</title>
    <style>
        :root {
            --cor-principal: #4CAF50;
            --cor-secundaria: #333;
            --cor-fundo: #f4f4f4;
            --cor-botao: #4CAF50;
            --cor-botao-hover: #45a049;
            --cor-bordas: #ddd;
            --cor-texto: #333;
            --cor-texto-secundario: #666;
            --cor-footer: #222;
            --cor-link: #4CAF50;
        }
        .container {
    display: grid;
    height: 800px;
    place-items: center;
    grid-template-columns: 18% 12% 65%;
    grid-template-areas:"section linha-vertical article";
}
.container section {
    width: 150px;
    display: grid;
    grid-area: section;
}
.container section>a {
    width: 150%;
    height: 45px;
    text-decoration: none;
    color: #ffffff;
    display: flex;
    gap: 15px;
    line-height: 30px;
    padding: 15px;
    font-size: 20px;
}

.container section a:hover {
    background-color: var(--verde);
    border-radius: 14px;
    color: white;
    transition: all 0.2s ease;
}

        body {
            font-family: 'Red Hat Display', sans-serif;
            background-color: var(--cor-fundo);
            margin: 0;
            padding: 0;
        }

        header {
            background-color: rgb(0, 0, 0);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .logo {
            height: 50px;
        }

        .submenu {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    background-color: var(--preto);
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
.submenu ul li p {
    color:#fff
}


.submenu ul li a{
    color: #000000;
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
    background-color: #ffffff;
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
        .corpo_principal {
            display: flex;
            flex-direction: column;
            padding: 20px;
            background-color: rgb(54, 54, 54);
        }

        .corpo_principal > article {
            margin-bottom: 20px;
        }

        .corpo_principal h1 {
            color:white ;
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .menu-lateral {
            background-color: #fff;
            border: 1px solid var(--cor-bordas);
            border-radius: 8px;
            padding: 20px;
        }

        .menu-lateral a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--cor-texto);
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .menu-lateral a:hover {
            background-color: var(--cor-principal);
            color: white;
        }

        .form-container {
            background-color: white;
            border: 1px solid var(--cor-bordas);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: var(--cor-principal);
        }

        .linha-horizontal {
            height: 2px;
            background-color: var(--cor-principal);
            margin: 10px 0;
        }

        .form-indicacao p {
            margin-bottom: 15px;
        }

        .form-indicacao strong {
            display: block;
            margin-bottom: 5px;
            color: var(--cor-secundaria);
        }

        .input-text {
            border: solid;
    height: 20px;
    width: 100%;
    background-color: var(--meiobranco);
        }

        .servicos-select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--cor-bordas);
            border-radius: 5px;
            font-size: 16px;
            color: var(--cor-texto);
        }

        button[type="button"],
        button[type="submit"] {
            background-color: var(--cor-botao);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button[type="button"]:hover,
        button[type="submit"]:hover {
            background-color: var(--cor-botao-hover);
        }

        .btn-salvar {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        footer {
            background-color: var(--cor-footer);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container-texto {
            display: flex;
            justify-content: space-around;
            padding: 20px;
        }

        .container-texto h3 {
            color: var(--cor-principal);
        }

        .container-texto a {
            color: var(--cor-link);
            text-decoration: none;
        }

        .container-texto a:hover {
            text-decoration: underline;
        }

        .segundo-rodape {
            background-color: var(--cor-footer);
            padding: 10px;
            color: white;
        }

        .segundo-rodape .icons a {
            color: white;
            margin: 0 10px;
            font-size: 24px;
            text-decoration: none;
        }

        .segundo-rodape .icons a:hover {
            color: var(--cor-link);
        }

        @media (max-width: 768px) {
            .container-texto {
                flex-direction: column;
                gap: 20px;
            }

            .menu-lateral {
                padding: 10px;
            }

            .form-container {
                padding: 15px;
            }
            .alert {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    color: #fff;
    font-weight: bold;
}

.alert.success {
    background-color: #4CAF50; 
}

.alert.error {
    background-color: #f44336; 
}

        }
    </style>
</head>
<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="Logo da página">
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
    </header>
    </div>
    <div class="corpo_principal">
    <article>
        <h1>Indicar</h1>
        <div class="linha-horizontal"></div>
    </article>

    
        
        <div class="container">

            </section>
            <article class="form-container">
                <h2><strong>Usuário | <?php echo htmlspecialchars($usuario['nome'] ?? ''); ?></strong></h2>
                <div class="linha-horizontal"></div>
                <h2><strong>Indicação de Empresa</strong></h2>
                <main class="form-indicacao">
                    <form action="processar_indicacao.php" method="POST">
                        <p>
                            <strong>Nome da Empresa:</strong>
                            <input type="text" class="input-text" name="nome_empresa" id="nome_empresa" required>
                        </p>
                        <p>
                            <strong>CNPJ:</strong>
                            <input type="text" class="input-text" name="cnpj" minlength="18" maxlength="18" oninput="formatarCNPJ(this)" id="cnpj" placeholder="Digite o CNPJ" required>
                            <button type="button" onclick="buscarDadosEmpresa()">Buscar</button>
                        </p>
                        <p>
                            <strong>Nome do Contato:</strong>
                            <input type="text" class="input-text" name="nome_contato" id="nome_contato" required>
                        </p>
                        <p>
                            <strong>Cargo do Contato:</strong>
                            <input type="text" class="input-text" name="cargo_contato" id="cargo_contato">
                        </p>
                        <p>
                            <strong>Número do Contato:</strong>
                            <input type="text" class="input-text" name="numero_contato" id="numero_contato" oninput="formatarNumeroContato(this)" required>
                        </p>
                        <p>
                            <strong>Email do Contato:</strong>
                            <input type="email" class="input-text" name="email_contato" id="email_contato" required>
                        </p>
                        <div id="servicos-container">
                            <div class="servico-group">
                                <label for="servicos">Serviço: </label>
                                <select name="servicos[]" class="servicos-select" required>
                                    <option value="">Selecione um Serviço</option>
                                    <?php
                                    include_once('../ScreenCadastro/config.php');
                                    $query_servicos = "SELECT * FROM servicos";
                                    $resultado_servicos = mysqli_query($conexao, $query_servicos);
                                    if (mysqli_num_rows($resultado_servicos) > 0) {
                                        while ($row = mysqli_fetch_assoc($resultado_servicos)) {
                                            echo '<option value="' . $row['id'] . '">' . $row['nome'] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="" disabled>Nenhum Serviço Cadastrado</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <button type="button" onclick="addServico()">Adicionar Mais Serviços</button>
                        <div class="btn-salvar">
                            <button type="submit">Enviar Indicação</button>
                        </div>
                        <?php if (!empty($mensagem)): ?>
        <?php echo $mensagem; ?>
    <?php endif; ?>
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
    <script>
    function buscarDadosEmpresa() {
        var cnpj = document.getElementById('cnpj').value;
        cnpj = cnpj.replace(/[^\d]/g, '');

        if (cnpj.length === 14) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'buscar_dados_empresa.php?cnpj=' + cnpj, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var dados = JSON.parse(xhr.responseText);
                        console.log('Dados recebidos:', dados);
                        if (dados.error) {
                            alert(dados.error);
                        } else {
                            document.getElementById('nome_empresa').value = dados.nome_empresa || '';
                        }
                    } catch (e) {
                        alert('Erro ao processar resposta JSON.');
                        console.error('Erro ao processar resposta JSON:', e);
                        console.log('Resposta recebida:', xhr.responseText);
                    }
                } else {
                    alert('Erro ao buscar dados da empresa.');
                    console.error('Erro HTTP:', xhr.status);
                    console.log('Resposta recebida:', xhr.responseText);
                }
            };
            xhr.onerror = function() {
                alert('Erro na requisição.');
            };
            xhr.send();
        } else {
            alert('CNPJ inválido');
        }
    }

    function addServico() {
        var container = document.getElementById('servicos-container');
        var uniqueId = 'servico_' + Date.now(); 

        var servicoGroup = document.createElement('div');
        servicoGroup.className = 'servico-group';
        servicoGroup.id = uniqueId;

        servicoGroup.innerHTML = `
            <label for="servicos">Serviço: </label>
            <select name="servicos[]" class="servicos-select" required>
                <option value="">Selecione um Serviço</option>
                <?php
                include_once('../ScreenCadastro/config.php');
                $query_servicos = "SELECT * FROM servicos";
                $resultado_servicos = mysqli_query($conexao, $query_servicos);
                if (mysqli_num_rows($resultado_servicos) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado_servicos)) {
                        echo '<option value="' . $row['id'] . '">' . $row['nome'] . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>Nenhum Serviço Cadastrado</option>';
                }
                ?>
            </select>
            <button type="button" class="remove-btn" onclick="removeServico('${uniqueId}')">Remover</button>
        `;

        container.appendChild(servicoGroup);
    }

    function removeServico(id) {
        var servicoGroup = document.getElementById(id);
        servicoGroup.remove();
    }

    function formatarNumeroContato(campo) {
        var valor = campo.value.replace(/\D/g, '');
        if (valor.length <= 11) {
            valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
        } else {
            valor = valor.replace(/^(\d{2})(\d{4})(\d{4})$/, "($1) $2-$3");
        }
        campo.value = valor;
    }

    function formatarCNPJ(campo) {
        campo.value = campo.value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "$1.$2.$3/$4-$5");
    }
    </script>
</body>
</html>
