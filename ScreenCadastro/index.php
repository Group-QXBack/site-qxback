<?php
include_once('config.php');
require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$erroSenha = '';
$erroConfirmSenha = '';
$erroCPF = '';
$erroEmail = '';
$erroIdade = '';
$mensagemSucesso = '';
$erroGenerico = '';

if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $genero = $_POST['genero'];
    $data_nasc = $_POST['data_nasc'];
    $senha = $_POST['senha'];
    $confirmSenha = $_POST['confirmSenha'];

    // Validação de idade
    $dataNascimento = new DateTime($data_nasc);
    $dataAtual = new DateTime();
    $idade = $dataAtual->diff($dataNascimento)->y;

    if ($idade < 18) {
        $erroIdade = "Você deve ter pelo menos 18 anos para se cadastrar.";
    }

    // Validação de senha
    $senhaValida = strlen($senha) >= 7 && preg_match('/[!@#$%^&*(),.?":{}|<>]/', $senha);
    $senhasCoincidem = $senha === $confirmSenha;

    if (!$senhaValida) {
        $erroSenha = "A senha deve ter pelo menos 7 caracteres e incluir pelo menos 1 caractere especial.";
    }

    if (!$senhasCoincidem) {
        $erroConfirmSenha = "As senhas não coincidem.";
    }

    // Validação de CPF
    $cpfQuery = "SELECT * FROM usuarios WHERE cpf = ?";
    $stmt = $conexao->prepare($cpfQuery);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $cpfResult = $stmt->get_result();

    if ($cpfResult->num_rows > 0) {
        $erroCPF = "Já existe uma conta cadastrada com este CPF.";
    }

    // Validação de E-mail
    $emailQuery = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($emailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $emailResult = $stmt->get_result();

    if ($emailResult->num_rows > 0) {
        $erroEmail = "Já existe uma conta cadastrada com este e-mail.";
    }
    if ($senhaValida && $senhasCoincidem && !$erroCPF && !$erroEmail && !$erroIdade) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16));
        $data_token = date('Y-m-d H:i:s');

        $query = "INSERT INTO usuarios(nome, sobrenome, cpf, email, data_nasc, senha, genero, tipo_conta, email_confirmado, token, data_token) VALUES (?, ?, ?, ?, ?, ?, ?, 'user', 0, ?, ?)";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("sssssssss", $nome, $sobrenome, $cpf, $email, $data_nasc, $senhaHash, $genero, $token, $data_token);

        if ($stmt->execute()) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'rodrigo.teste0104@gmail.com'; 
                $mail->Password = 'fckh pnvn rqhv tpuk'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('rodrigo.teste0104@gmail.com');
                $mail->addAddress($email);
                $logoPath = 'http://qxback.com.br/imagens/logobranca1.png';
                $mail->isHTML(true);
                $mail->Subject = 'Ativar Cadastro';
                $mail->Body = '
                <div style="font-family: \'Montserrat\', sans-serif; background-color: #f4f4f4; color: #333; padding: 20px; text-align: center;">
                    <img src="' . $logoPath . '" alt="Logo" style="max-width: 200px; margin: 20px 0;">
                    <h1 style="color: #44ff00;">Bem-vindo ao nosso serviço!</h1>
                    <p>Para ativar sua conta, por favor, clique no link abaixo:</p>
                    <p>
                        <a href="http://qxback.com.br/ScreenCadastro/confirmar.php?email=' . urlencode($email) . '&token=' . $token . '" style="text-decoration: none; color: #44ff00; font-weight: bold;">Confirmar E-mail</a>
                    </p>
                    <p>Se você não se cadastrou, ignore este e-mail.</p>
                    <p>Agradecemos a sua escolha!</p>
                </div>
                ';
                $mail->AltBody = 'Por favor, copie e cole o seguinte link em seu navegador: http://qxback.com.br/ScreenCadastro/confirmar.php?email=' . urlencode($email);

                $mail->send();
                $mensagemSucesso = "Cadastro realizado com sucesso! Um e-mail de confirmação foi enviado para o seu endereço.";
                
                header("Location: sucesso.php");
                exit();
            } catch (Exception $e) {
                $erroGenerico = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
            }
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
    <title>Cadastro</title>
    
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
            <img src="../imagens/qxback-img.png" alt="Imagem do Formulário">
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
                        <input id="nome" type="text" name="nome" minlength="3" maxlength="50" placeholder="Digite seu primeiro nome" required>
                    </div>
                    <div class="input-box">
                        <label for="sobrenome"><h3>Sobrenome</h3></label>
                        <input id="sobrenome" type="text" name="sobrenome" minlength="3" maxlength="50" placeholder="Digite seu Sobrenome" required>
                    </div>
                    <div class="input-box">
                        <label for="cpf"><h3>CPF</h3></label>
                        <input type="text" name="cpf" minlength="14" maxlength="14" id="cpf" placeholder="Digite seu CPF" oninput="formatarCPF(this)" required>
                        <div id="error-cpf" class="error-message">
                            <?php if (!empty($erroCPF)) { echo $erroCPF; } ?>
                        </div>
                    </div>  

                    <div class="input-box">
                        <label for="email"><h3>E-mail</h3></label>
                        <input id="email" type="email" name="email" placeholder="Digite seu e-mail" required>
                        <div id="error-email" class="error-message">
                            <?php if (!empty($erroEmail)) { echo $erroEmail; } ?>
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="data_nasc"><h3>Data de Nascimento</h3></label>
                        <input id="data_nasc" type="date" name="data_nasc" required>
                        <div id="error-idade" class="error-message">
                            <?php if (!empty($erroIdade)) { echo $erroIdade; } ?>
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="senha"><h3>Senha</h3></label>
                        <input id="senha" type="password" name="senha" minlength="7" maxlength="30" placeholder="Digite sua senha" required>
                        <p style="font-size: 10px;">Deve conter mais de 7 caracteres, letra maiuscula e pelo menos um caracter especial</p>
                        <div id="error-senha" class="error-message">
                            <?php if (!empty($erroSenha)) { echo $erroSenha; } ?>
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="confirmSenha"><h3>Confirme sua Senha</h3></label>
                        <input id="confirmSenha" type="password" name="confirmSenha" placeholder="Confirme sua senha" required>
                        <div id="error-confirmSenha" class="error-message">
                            <?php if (!empty($erroConfirmSenha)) { echo $erroConfirmSenha; } ?>
                        </div>
                    </div>
                </div>

                <div class="genero-inputs">
                    <div class="genero-title">
                        <h3>Gênero</h3>
                    </div>
                    <div class="genero">
                        <div class="genero-input">
                            <input id="feminino" value="feminino" type="radio" name="genero" required>
                            <label for="feminino"><h3>Feminino</h3></label>
                        </div>
                        <br>
                        <div class="genero-input">
                            <input id="masculino" value="masculino" type="radio" name="genero" required>
                            <label for="masculino"><h3>Masculino</h3></label>
                        </div> 
                        <br>
                        <div class="genero-input">
                            <input id="nao-declarado" value="Não declarado" type="radio" name="genero" required>
                            <label for="nao-declarado"><h3>Não declarar</h3></label>
                        </div>
                    </div>
                </div>

                <div class="checkbox">
                    <input type="checkbox" id="termos" name="termos" required/>
                    <label for="termos-de-uso">Eu li e concordo com todos os termos e condições</label>
                </div>

                <div class="continue-button">
                    <button type="submit" name="submit">Cadastrar</button>
                </div>
                <br>
                <div class="entrar-txt">
                    <h4>Ou já tem uma conta? <a href="../ScreenLogin/index.html"><strong>Entrar</strong></a></h4>
                </div>

                <?php if (!empty($mensagemSucesso)) { ?>
                    <div class="success-message">
                        <?php echo $mensagemSucesso; ?>
                    </div>
                <?php } ?>
                <?php if (!empty($erroGenerico)) { ?>
                    <div class="error-message">
                        <?php echo $erroGenerico; ?>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>
</body>
</html>
