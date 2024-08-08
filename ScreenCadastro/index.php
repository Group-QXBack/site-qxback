<?php
include_once('config.php'); 

$erroSenha = '';
$erroConfirmSenha = '';
$mensagemSucesso = '';

if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $genero = $_POST['genero'];
    $data_nasc = $_POST['data_nasc'];
    $senha = $_POST['senha'];
    $confirmSenha = $_POST['confirmSenha']; 

    $senhaValida = strlen($senha) >= 7;
    $senhasCoincidem = $senha === $confirmSenha;

    if (!$senhaValida) {
        $erroSenha = "A senha deve ter pelo menos 7 caracteres.";
    }
    
    if (!$senhasCoincidem) {
        $erroConfirmSenha = "As senhas não coincidem.";
    }

    if ($senhaValida && $senhasCoincidem) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios(nome, cpf, email, data_nasc, senha, genero) VALUES ('$nome', '$cpf', '$email', '$data_nasc', '$senhaHash', '$genero')";
        $result = mysqli_query($conexao, $query);

        if ($result) {
            $mensagemSucesso = "Cadastro realizado com sucesso!";
            header('Location: ../ScreenLogin/index.html?error=Cadastro feito com sucesso.');
        } else {
            $erroGenerico = "Erro ao cadastrar - " . mysqli_error($conexao);
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
    <link rel="stylesheet" href="./style.php">
    <title>Cadastro</title>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 0.5em;
        }
        .success-message {
            color: green;
            font-size: 0.9em;
            margin-top: 0.5em;
        }
    </style>
    <script>
        function formatarCPF(campo) {
            campo.value = campo.value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        }

        function validarFormulario() {
            var senha = document.getElementById('senha').value;
            var confirmSenha = document.getElementById('confirmSenha').value;
            var senhaValida = senha.length >= 7;

            var erro = false;
            if (senha !== confirmSenha) {
                document.getElementById('error-confirmSenha').textContent = 'As senhas não coincidem.';
                erro = true;
            } else {
                document.getElementById('error-confirmSenha').textContent = '';
            }

            if (!senhaValida) {
                document.getElementById('error-senha').textContent = 'A senha deve ter pelo menos 7 caracteres.';
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
            <img src="../imagens/ImagemCadastro.png" alt="">
        </div>
        <div class="form">
            <form action="index.php" method="POST" onsubmit="return validarFormulario()">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>
                    <div class="login-button">
                        <button><a href="../ScreenLogin/index.html">Entrar</a></button>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="nome">Nome</label>
                        <input id="nome" type="text" name="nome" minlength="8" maxlength="50" placeholder="Digite seu nome completo" required>
                    </div>

                    <div class="input-box">
                        <label for="cpf">CPF</label>
                        <input type="text" name="cpf" minlength="14" maxlength="14" id="cpf" class="inputUser" oninput="formatarCPF(this)" required>
                    </div>

                    <div class="input-box">
                        <label for="email">E-mail</label>
                        <input id="email" type="email" name="email" placeholder="Digite seu e-mail" required>
                    </div>

                    <div class="input-box">
                        <label for="data_nasc">Data de Nascimento</label>
                        <input id="data_nasc" style="width: 225px;" type="date" name="data_nasc" placeholder="XX/XX/XXXX" required>
                    </div>

                    <div class="input-box">
                        <label for="senha">Senha</label>
                        <input id="senha" type="password" name="senha" minlength="7" maxlength="30" placeholder="Digite sua senha" required pattern=".{7,}" title="A senha deve ter pelo menos 7 caracteres">
                        <div id="error-senha" class="error-message">
                            <?php if (isset($erroSenha)) { echo $erroSenha; } ?>
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="confirmSenha">Confirme sua Senha</label>
                        <input id="confirmSenha" type="password" name="confirmSenha" placeholder="Digite sua senha novamente" required>
                        <div id="error-confirmSenha" class="error-message">
                            <?php if (isset($erroConfirmSenha)) { echo $erroConfirmSenha; } ?>
                        </div>
                    </div>
                </div>

                <div class="genero-inputs">
                    <div class="genero-title">
                        <h6>Gênero</h6>
                    </div>
                    <div class="genero">
                        <div class="genero-input">
                            <input id="feminino" value="feminino" type="radio" name="genero">
                            <label for="feminino">Feminino</label>
                        </div>

                        <div class="genero-input">
                            <input id="masculino" value="masculino" type="radio" name="genero">
                            <label for="masculino">Masculino</label>
                        </div>

                        <div class="genero-input">
                            <input id="outros" value="outros" type="radio" name="genero">
                            <label for="outros">Outros</label>
                        </div>
                    </div>
                </div>

                <div class="continue-button">
                    <button type="submit" name="submit">Continuar</button>
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
