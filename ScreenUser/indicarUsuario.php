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
            <h1>Transforme conexões em recompensas!</h1>
            </div>
            <div class="form-indicacao">
    <form action="processar_indicacao.php" method="POST">
        <p>
            <strong class="dados">Nome da Empresa:</strong>
            <input type="text" class="input-text" name="nome_empresa" id="nome_empresa" required>
        </p>
        <p>
            <strong class="dados">CNPJ:</strong>
            <input type="text" class="input-text" name="cnpj" minlength="18" maxlength="18" oninput="formatarCNPJ(this)" id="cnpj" placeholder="Digite o CNPJ" required>
            <button type="button" onclick="buscarDadosEmpresa()">Buscar</button>
        </p>
        <p>
            <strong class="dados">Nome do Contato:</strong>
            <input type="text" class="input-text" name="nome_contato" id="nome_contato" required>
        </p>
        <p>
            <strong class="dados">Cargo do Contato:</strong>
            <input type="text" class="input-text" name="cargo_contato" id="cargo_contato">
        </p>
        <p>
            <strong class="dados">Número do Contato:</strong>
            <input type="text" class="input-text" name="numero_contato" id="numero_contato" oninput="formatarNumeroContato(this)" required>
        </p>
        <p>
            <strong class="dados">Email do Contato:</strong>
            <input type="email" class="input-text" name="email_contato" id="email_contato" required>
        </p>
        <div id="servicos-container">
    <div class="servico-group" style="margin-top: 10px;">
        <strong class="dados">Serviço: </strong>
        <select id="servicos" name="servicos[]" class="servicos-select" required>
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
            <script>
        $(document).ready(function() {
        // Ativar o Select2 para o campo de seleção com busca
        $('#servicos').select2({
            placeholder: "Selecione um Serviço",
            allowClear: true,
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado";
                }
            }
        });
    });
</script>
            </select>
        </div>
    </div>
        <div class="botoes">
            <button type="button" onclick="addServico()" style="background-color:#8f8f8f;">Adicionar Mais Serviços</button>
        </div>
        <div class="botoes">
            <button type="submit">Enviar Indicação</button>
        </div>
        <?php if (!empty($mensagem)): ?>
            <?php echo $mensagem; ?>
        <?php endif; ?>
    </form>
</div>
        </div>
    </section>
        <footer class="primeiro-rodape">
            <article>
                <div class="container-texto">
                    <div class="primeiro-txt">
                        <h3>
                            <strong style="cursor: default; color: #1bff1b;">QXBack</strong>
                        </h3>
                        <p>
                            <a href="#">Programa</a>
                            <a href="#">Como indicar?</a>
                        </p>
                    </div>
                    <div class="segundo-txt">
                        <h3>
                            <strong style="cursor: default; color: #1bff1b;">Serviços</strong>
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

    // Criar o HTML do select e botão de remover
    servicoGroup.innerHTML = `
        <label for="servicos">Serviço: </label>
        <select id="${uniqueId}_select" name="servicos[]" class="servicos-select" required>
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

    // Adicionar o novo grupo de serviço ao container
    container.appendChild(servicoGroup);

    // Aplicar o Select2 ao novo select
    $('#' + uniqueId + '_select').select2({
        placeholder: "Selecione um Serviço",
        allowClear: true,
        language: {
            noResults: function () {
                return "Nenhum resultado encontrado";
            }
        }
    });
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