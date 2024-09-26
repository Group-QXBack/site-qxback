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
        <link rel="stylesheet" href="./styleIndicar.css">
        <link rel="shortcut icon" href="img/i   con_uu.webp" type="image/x-icon">
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
                    <a href="minhas_indicacoes.php">
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
                <div class="linha-vertical"></div>
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
                            <input type="text" class="input-text" minlength="10" maxlength="14" name="numero_contato" id="numero_contato" oninput="formatarNumeroContato(this)" required>
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
