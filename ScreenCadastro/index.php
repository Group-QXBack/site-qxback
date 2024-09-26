<?php
include_once('config.php');

$erroSenha = '';
$erroConfirmSenha = '';
$erroCPF = '';
$erroEmail = '';
$erroIdade = '';
$mensagemSucesso = '';
$erroGenerico = '';

if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $genero = $_POST['genero'];
    $data_nasc = $_POST['data_nasc'];
    $senha = $_POST['senha'];
    $confirmSenha = $_POST['confirmSenha'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $dataNascimento = new DateTime($data_nasc);
    $dataAtual = new DateTime();
    $idade = $dataAtual->diff($dataNascimento)->y;

    if ($idade < 18) {
        $erroIdade = "Você deve ter pelo menos 18 anos para se cadastrar.";
    }

    $senhaValida = strlen($senha) >= 7 && preg_match('/[!@#$%^&*(),.?":{}|<>]/', $senha);
    $senhasCoincidem = $senha === $confirmSenha;

    if (!$senhaValida) {
        $erroSenha = "A senha deve ter pelo menos 7 caracteres e incluir pelo menos 1 caractere especial.";
    }

    if (!$senhasCoincidem) {
        $erroConfirmSenha = "As senhas não coincidem.";
    }

    $cpfQuery = "SELECT * FROM usuarios WHERE cpf = ?";
    $stmt = $conexao->prepare($cpfQuery);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $cpfResult = $stmt->get_result();

    if ($cpfResult->num_rows > 0) {
        $erroCPF = "Já existe uma conta cadastrada com este CPF.";
    }

    $emailQuery = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($emailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $emailResult = $stmt->get_result();

    if ($emailResult->num_rows > 0) {
        $erroEmail = "Já existe uma conta cadastrada com este e-mail.";
    }

    $recaptchaSecret = '6Le78UAqAAAAAJc79C0mvT0pkHdJ0kj7LygcoJu1'; 
    $recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($recaptchaVerifyUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys['success']) {
        $erroGenerico = 'Verificação do reCAPTCHA falhou. Tente novamente.';
    }

    if ($senhaValida && $senhasCoincidem && !$erroCPF && !$erroEmail && !$erroIdade && !$erroGenerico) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios(nome, cpf, email, data_nasc, senha, genero, tipo_conta) VALUES (?, ?, ?, ?, ?, ?, 'user')";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("ssssss", $nome, $cpf, $email, $data_nasc, $senhaHash, $genero);

        if ($stmt->execute()) {
            $mensagemSucesso = "Cadastro realizado com sucesso!";
            echo "<script>
                    window.onload = function() {
                        if (typeof gtag === 'function') {
                            gtag('event', 'reCAPTCHA_success', {
                              'event_category': 'Formulário',
                              'event_label': 'Cadastro',
                              'value': 1
                            });
                        } else {
                            console.error('Google Analytics gtag function is not available.');
                        }
                    };
                  </script>";
        } else {
            $erroGenerico = "Erro ao cadastrar - " . $conexao->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style1.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Cadastro</title>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MCQCCY98ZL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MCQCCY98ZL');
</script>
    <script>
        function formatarCPF(campo) {
            campo.value = campo.value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        }

        function validarFormulario() {
            var senha = document.getElementById('senha').value;
            var confirmSenha = document.getElementById('confirmSenha').value;

            var senhaValida = senha.length >= 7 && /[!@#$%^&*(),.?":{}|<>]/.test(senha);

            var erro = false;
            if (senha !== confirmSenha) {
                document.getElementById('error-confirmSenha').textContent = 'As senhas não coincidem.';
                erro = true;
            } else {
                document.getElementById('error-confirmSenha').textContent = '';
            }

            if (!senhaValida) {
                document.getElementById('error-senha').textContent = 'A senha deve ter pelo menos 7 caracteres e incluir pelo menos 1 caractere especial.';
                erro = true;
            } else {
                document.getElementById('error-senha').textContent = '';
            }

            return !erro;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-image">
            <img src="../imagens/qxback-2.png" alt="Imagem do Formulário">
        </div>
        <div class="form">
            <form action="index.php" method="POST" onsubmit="return validarFormulario()">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="nome"><h3>Nome</h3></label>
                        <input id="nome" type="text" name="nome" minlength="8" maxlength="50" placeholder="Digite seu nome completo" required>
                    </div>
                    <div class="input-box">
                        <label for="cpf"><h3>CPF</h3></label>
                        <input type="text" name="cpf" minlength="14" maxlength="14" id="cpf" placeholder="Digite seu CPF" oninput="formatarCPF(this)" required>
                        <div id="error-cpf" class="error-message">
                            <?php if (isset($erroCPF)) { echo $erroCPF; } ?>
                        </div>
                    </div>  

                    <div class="input-box">
                        <label for="email"><h3>E-mail</h3></label>
                        <input id="email" type="email" name="email" placeholder="Digite seu e-mail" required>
                        <div id="error-email" class="error-message">
                            <?php if (isset($erroEmail)) { echo $erroEmail; } ?>
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="data_nasc"><h3>Data de Nascimento</h3></label>
                        <input id="data_nasc" type="date" name="data_nasc" placeholder="dd/mm/aaaa" required>
                        <div id="error-idade" class="error-message">
                            <?php if (isset($erroIdade)) { echo $erroIdade; } ?>
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="senha"><h3>Senha</h3></label>
                        <input id="senha" type="password" name="senha" minlength="7" maxlength="30" placeholder="Digite sua senha" required>
                        <div id="error-senha" class="error-message">
                            <?php if (isset($erroSenha)) { echo $erroSenha; } ?>
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="confirmSenha"><h3>Confirme sua Senha</h3></label>
                        <input id="confirmSenha" type="password" name="confirmSenha" placeholder="Confirme sua senha" required>
                        <div id="error-confirmSenha" class="error-message">
                            <?php if (isset($erroConfirmSenha)) { echo $erroConfirmSenha; } ?>
                        </div>
                    </div>
                </div>

                <div class="genero-inputs">
                    <div class="genero-title">
                        <h3>Gênero</h3>
                    </div>
                    <div class="genero">
                        <div class="genero-input">
                            <input id="feminino" value="feminino" type="radio" name="genero">
                            <label for="feminino"><h3>Feminino</h3></label>
                        </div>
                        <br>
                        <div class="genero-input">
                            <input id="masculino" value="masculino" type="radio" name="genero">
                            <label for="masculino"><h3>Masculino</h3></label>
                        </div> 
                        <br>
                        <div class="genero-input">
                            <input id="nao-declarado" value="Não declarado" type="radio" name="genero">
                            <label for="nao-declarado"><h3>Não declarar</h3></label>
                        </div>
                        <div class="g-recaptcha" data-sitekey="6Le78UAqAAAAAMI-GeVanGrsirH-otodLUMmYMny"></div>
                    </div>
                </div>

                <div class="continue-button">
                    <button type="submit" name="submit">Cadastrar</button>
                </div>
                <br>
                <div class="entrar-txt">
                    <h4>Ou já tem uma conta? <a href="../ScreenLogin/index.html"><strong>Entrar</strong></a></h4>
                </div>

                <?php if (isset($mensagemSucesso)) { ?>
                    <div class="success-message">
                        <?php echo $mensagemSucesso; ?>
                    </div>
                <?php } ?>
                <?php if (isset($erroGenerico)) { ?>
                    <div class="error-message">
                        <?php echo $erroGenerico; ?>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>
</body>
</html>
