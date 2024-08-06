<?php

    if(isset($_POST ['submit']))
    {

        include_once('config.php');


        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $cpf = $_POST['cpf'];
        $genero = $_POST['genero'];
        $data_nasc = $_POST['data_nasc'];
        $senha = $_POST['senha'];

        $result = mysqli_query($conexao, "INSERT INTO usuarios(nome,cpf,email,data_nasc,senha,genero) 
        VALUES ('$nome','$cpf','$email','$data_nasc','$senha','$genero')");

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
</head>
<script>
        function formatarCPF(campo) {
            campo.value = campo.value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        }
</script>

<body>
    <div class="container">
        <div class="form-image">
            <img src="../imagens/ImagemCadastro.png" alt="">
        </div>
        <div class="form">
            <form action="index.php" method="POST">
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
                        <input id="nome" type="text" name="nome" placeholder="Digite seu nome" required>
                    </div>

                    <div class="input-box">
                        <label for="cpf">CPF</label>
                        <input type="text" name="cpf" size="14" maxlength="14" id="cpf" class="inputUser" oninput="formatarCPF(this)" required>
                    </div>
                    <div class="input-box">
                        <label for="email">E-mail</label>
                        <input id="email" type="email" name="email" placeholder="Digite seu e-mail" required>
                    </div>

                    <div class="input-box">
                        <label for="data_nasc">Data de Nascimento</label>
                        <input id="data_nasc" type="date" name="data_nasc" placeholder="XX/XX/XXXX" required>
                    </div>

                    <div class="input-box">
                        <label for="senha">Senha</label>
                        <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="input-box">
                        <label for="confirmSenha">Confirme sua Senha</label>
                        <input id="confirmSenha" type="password" name="senha" placeholder="Digite sua senha novamente" required>
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
            </form>
        </div>
    </div>
</body>

</html>