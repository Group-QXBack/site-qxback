<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'user') {
    header("Location: ../ScreenUser/index.php");
    exit();
}
$usuario = $_SESSION['usuario'];
$errors = [];

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

    $nome_titular = $_POST['nome'] ?? '';
    $cpf_titular = $_POST['sobrenome'] ?? '';
    $banco = $_POST['email'] ?? '';
    $conta = $_POST['dtnasc'] ?? '';
    $agencia = $_POST['agencia'] ?? '';

    if (empty($telefone) || empty($cep) || empty($endereco) || empty($estado) || empty($complemento) || empty($cidade) || empty($bairro) || empty($nome_titular) || empty($cpf_titular) || empty($banco) || empty($conta) || empty($agencia)) {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf_titular)) {
        $errors[] = 'CPF inválido.';
    }

    if (empty($errors)) {
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

            $stmt = $conexao->prepare("
            INSERT INTO contas_bancarias (usuario_id, nome_titular, cpf_titular, banco, conta, agencia)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                nome_titular = VALUES(nome_titular),
                cpf_titular = VALUES(cpf_titular),
                banco = VALUES(banco),
                conta = VALUES(conta),
                agencia = VALUES(agencia)
        ");              
            $stmt->bind_param('isssss', $usuario['id'], $nome_titular, $cpf_titular, $banco, $conta, $agencia);

            if ($stmt->execute()) {
                $success_message = 'Dados atualizados com sucesso!';
            } else {
                $errors[] = 'Ocorreu um erro ao atualizar seus dados bancários. Tente novamente.';
            }
        } else {
            $errors[] = 'Ocorreu um erro ao atualizar seus dados pessoais. Tente novamente.';
        }
    }
}
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $banconumero = $_POST['banconumero'];
                $agencia = $_POST['agencia'];
                $contanumero = $_POST['contanumero'];

                if (validateBancoDetails($banconumero, $agencia, $contanumero)) {
                    echo "Conta Bancária válida";
                } else {
                    echo "Conta Bancária inválida";
                }
            }
            function validateBancoDetails($banconumero, $agencia, $contanumero) {
                return preg_match('/^[0-9]{3}$/', $banconumero) &&
                preg_match('/^[0-9]{4}$/', $agencia) &&
                preg_match('/^[0-9]{6,10}$/', $contanumero);
       }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../ScreenUser/styleEditar.css">
    <link rel="shortcut icon" href="img/icon_uu.webp" type="image/x-icon">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 
    <script src="https://kit.fontawesome.com/af6c14a78e.js" crossorigin="anonymous"></script>
    <title>Completar Cadastro</title>
    </head>

    <body>
        <div class="header" id="header">
        <button onclick="toggleSidebar()" class="btn_icon_header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>
        <div class="logo_header">
            <img src="../imagens/logobranca1.png" alt="Logo" class="img_logo_header">
        </div>
        <div class="navigation_header" id="navigation_header">
            <button onclick="toggleSidebar()" class="btn_icon_header">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
            <a href="./index.php" id="btn-minhaConta" style="border-radius: 14px;">
            <p>Minha Conta</p>
            </a>
            <a href="./minhas_indicacoes.php">
            <p>Minhas Indicações</p>
            </a>
            <a href="./indicar.php">
            <p>Indicar</p>
            </a>
        </div>
    </div>
    <div class="content">
        <form method="POST" action="">
        <fieldset>
                <legend>Dados Pessoais</legend>
                <div class="row">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" class="input-text" oninput="formatarTelefone(this)" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" oninput="formatarCEP(this)" class="input-text" value="<?php echo htmlspecialchars($usuario['cep'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" class="input-text" value="<?php echo htmlspecialchars($usuario['endereco'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" name="estado" class="input-text" value="<?php echo htmlspecialchars($usuario['estado'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" class="input-text" value="<?php echo htmlspecialchars($usuario['cidade'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" name="bairro" class="input-text" value="<?php echo htmlspecialchars($usuario['bairro'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="complemento">Complemento</label>
                    <input type="text" id="complemento" name="complemento" class="input-text" value="<?php echo htmlspecialchars($usuario['complemento'] ?? ''); ?>">
                </div>
            </fieldset>
            <fieldset>
                <form id="bancoForm">
                <legend>Dados Bancários</legend>
                <div class="row">
                    <label for="nome">Nome do Titular</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite o nome do titular" autocomplete="off" value="<?php echo htmlspecialchars($conta_bancaria['nome_titular'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="sobrenome">CPF</label>
                    <input type="text" id="sobrenome" name="sobrenome" minlength="14" maxlength="14" oninput="formatarCPF(this)" autocomplete="off" value="<?php echo htmlspecialchars($conta_bancaria['cpf_titular'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="banconumero">Número do Banco</label>
                    <input type="text" id="banconumero" name="banconumero" autocomplete="off" value="<?php echo htmlspecialchars($conta_bancaria['banco'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="numeroconta">Número da Conta</label>
                    <input type="number" id="numeroconta" name="numeroconta" value="<?php echo htmlspecialchars($conta_bancaria['conta'] ?? ''); ?>" onblur="buscarDadosBancarios()">
                </div>
                <div class="row">
                    <label for="agencia">Agência</label>
                    <input type="number" id="agencia" name="agencia" value="<?php echo htmlspecialchars($conta_bancaria['agencia'] ?? ''); ?>">
                </div>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="<?php echo $_SESSION['message']['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
                        <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
                    </div>
                <?php endif; ?>
                </form>
            </fieldset>

            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <button type="submit">Atualizar</button>
        </form>
    </div>
</div>

<footer class="primeiro-rodape">
            <div class="container-texto">
                <div class="primeiro-txt">
                    <h3>
                        <strong style="cursor: default; color: #1bff1b;">QXBack</strong>
                    </h3>
                    <p>
                        <a href="#">Programa</a>
                        <a href="#">Como indicar?</a>
                        <a href="#">Portal de indicações</a>
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
            var header           = document.getElementById('header');
    var navigationHeader = document.getElementById('navigation_header');
    var content          = document.getElementById('content');
    var showSidebar      = false;

    function toggleSidebar()
    {
        showSidebar = !showSidebar;
        if(showSidebar)
        {
            navigationHeader.style.marginLeft = '-10vw';
            navigationHeader.style.animationName = 'showSidebar';
            content.style.filter = 'blur(2px)';
        }
        else
        {
            navigationHeader.style.marginLeft = '-100vw';
            navigationHeader.style.animationName = '';
            content.style.filter = '';
        }
    }

    function closeSidebar()
    {
        if(showSidebar)
        {
            showSidebar = true;
            toggleSidebar();
        }
    }

    window.addEventListener('resize', function(event) {
        if(window.innerWidth > 768 && showSidebar) 
        {  
            showSidebar = true;
            toggleSidebar();
        }
    });

        document.addEventListener('DOMContentLoaded', function() {
            const cepInput = document.getElementById('cep');
            cepInput.addEventListener('blur', function() {
                const cep = this.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById('endereco').value = data.logradouro;
                                document.getElementById('bairro').value = data.bairro;
                                document.getElementById('cidade').value = data.localidade;
                                document.getElementById('estado').value = data.uf;
                            } else {
                                alert('CEP não encontrado.');
                            }
                        })
                        .catch(() => alert('Erro ao buscar CEP.'));
                }
            });
        });
        function formatarTelefone(campo) {
            campo.value = campo.value.replace(/\D/g, '');
            
            campo.value = campo.value.substring(0, 14);
            
            if (campo.value.length > 2) {
                campo.value = campo.value.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
            }
        }
        function formatarCEP(campo) {
            campo.value = campo.value.replace(/\D/g, '');
            
            campo.value = campo.value.substring(0, 8);
            
            if (campo.value.length > 5) {
                campo.value = campo.value.replace(/^(\d{5})(\d{1,3})$/, "$1-$2");
            }
        }
        function formatarCPF(campo) {
            campo.value = campo.value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        }
        document.getElementById('bancoForm').addEventListener('submit', function(event){
            event.preventDefault();
            const banconumero = document.getElementById('banconumero').value;
            const numeroconta = document.getElementById('numeroconta').value;
            const agencia = document.getElementById('agencia').value;
        if (validateBancoDetails(banconumero, agencia, numeroconta)) {
            alert('Conta bancária válida');
        } else {
            alert('Conta bancária inválida')
        }
        });
        function validateBancoDetails(banconumero, agencia, numeroconta) {
            const bancoRegex = /^[0-9]{3}$/;
            const agenciaRegex = /^[0-9][4]$/;
            const contaRegex= /^[0-9]{6,10}$/;
            
            return bancoRegex.test(banconumero) && agencyRegex.test(agencia) &&
            accountRegex.test(numeroconta);
        }
    </script>
</body>
</html>