<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'admin') {
    header("Location: ../ScreenUser/index.html");
    exit();
}

if (!empty($_GET['id'])) {
    include_once('../ScreenCadastro/config.php');

    $id = $_GET['id'];

    $sqlSelect = "SELECT * FROM usuarios WHERE id=?";
    $stmt = $conexao->prepare($sqlSelect);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $nome = $user_data['nome'];
        $cpf = $user_data['cpf'];
        $email = $user_data['email'];
        $telefone = $user_data['telefone'];
        $data_nasc = $user_data['data_nasc'];
        $genero = $user_data['genero'];
        $cep = $user_data['cep'];
        $cidade = $user_data['cidade'];
        $estado = $user_data['estado'];
        $bairro = $user_data['bairro'];
        $endereco = $user_data['endereco'];
        $numero = $user_data['numero'];
        $complemento = $user_data['complemento'];
        $tipo_conta = $user_data['tipo_conta'];
    } else {
        header('Location: cadastros.php');
        exit();
    }
} else {
    header('Location: cadastros.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cadastro de Usuario</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-color: #4a4a4a;
            color: whitesmoke;
            background-size: cover; 
            background-attachment: fixed; 
            margin: 0; 
            padding: 0; 
        }
        .box{
            color: aliceblue;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.4);
            padding: 10px;
            border-radius: 4px;
            width: 335px;
            font-size: 14px;
            text-align: center;
        }
        fieldset{
            border: 1px solid rgb(113, 202, 150);
            border-radius: 4px;
        }
        legend{
            border: 1px solid rgb(113, 202, 150);
            padding: 5px;
            width: 250px;
            text-align: center;
            background-color: rgb(113, 202, 150);
            border-radius: 4px;
        }
        .inputBox{
            position: absolute;
        }
        .inputUser{
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: aliceblue;
            font-size: 15px;
            width: 300px;
            letter-spacing: 1px;
        }
        .labelInput{
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .3s;
        }
        .inputUser:focus ~ .labelInput, 
        .inputUser:valid ~ .labelInput{
            top: -10px;
            font-size: 10px;
            color: rgb(0, 225, 255);
        }
        #data_nasc{
            color: black;
            border: none;
            padding: 2px;
            background-color: aliceblue;
            width: fit-content;
            outline: none;
            font-size: 14px;
        }
        #submit{
            text-decoration: none;
            width: 200px;
            border-radius: 4px;
            padding: 8px;
            color: white;
            font-size: 14px;
            background-color: rgb(0, 0, 0, 0.2);
        }
        #submit:hover{
            background-color: rgb(75, 198, 133);
        }
        #update{
            text-decoration: none;
            width: 200px;
            border-radius: 4px;
            padding: 8px;
            color: white;
            font-size: 14px;
            background-color: rgb(0, 0, 0, 0.2);
        }
        #update:hover{
            background-color: rgb(75, 198, 133);
        }
        a{
            text-decoration: none;
            border-radius: 4px;
            padding: 8px;
            color: white;
            font-size: 14px;
            background-color: rgb(0, 0, 0, 0.2);
        }
        a:hover{
            background-color: rgb(10, 100, 150);
        }
    </style>
</head>
<body>
    <div class="box">
        <form action="saveedit.php" method="POST">
            <fieldset>
                <legend><b>Editar Cadastro</b></legend>
                <label for="nome">Nome completo:</label><br>
                <input type="text" name="nome" id="nome" value="<?php echo $nome; ?>" ><br>
                
                <label for="cpf">CPF com pontuação:</label><br>
                <input type="text" name="cpf" id="cpf" value="<?php echo $cpf; ?>" oninput="formatarCPF(this)" ><br>
                
                <label for="email">E-mail:</label><br>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>" required><br>
                
                <label for="telefone">Celular:</label><br>
                <input type="tel" name="telefone" minlenght="15" maxlength="15" id="telefone" value="<?php echo $telefone; ?>" oninput="formatarTelefone(this)" ><br><br>
                
                <div class="inputBox">
                    <label for="data_nasc">Data de Nascimento: </label>
                    <input type="date" name="data_nasc" id="data_nasc" class="inputUser" value="<?php echo $data_nasc; ?>" >
                </div>
                <br><br>
                
                <div class="inputBox">
                    <label for="genero">Gênero: </label>
                    <select name="genero" required>
                        <option value="">Selecione...</option>
                        <option value="feminino" <?php echo ($genero == 'feminino') ? 'selected' : ''; ?>>Feminino</option>
                        <option value="masculino" <?php echo ($genero == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="outros" <?php echo ($genero == 'outros') ? 'selected' : ''; ?>>Outros</option>
                    </select>
                </div>
                <br><br>
                <div class="inputBox">
                <input type="text" name="cep" id="cep" oninput="formatarCEP(this)" class="inputUser" value="<?php echo $cep; ?>">
                <label for="cep" class="labelInput">CEP</label>
            </div>  
            <br><br>
                               <div class="inputBox">
                    <input type="text" name="estado" id="estado" class="inputUser" value="<?php echo $estado; ?>" >
                    <label for="estado" class="labelInput">Estado</label>
                </div>
                <br><br>
                <div class="inputBox">
                    <input type="text" name="cidade" id="cidade" class="inputUser" value="<?php echo $cidade; ?>" >
                    <label for="cidade" class="labelInput">Cidade</label>
                </div>
                <br> <br>
                <div class="inputBox">
                    <input type="text" name="bairro" id="bairro" class="inputUser" value="<?php echo $bairro; ?>" >
                    <label for="bairro" class="labelInput">Bairro</label>
                </div>
 
                <br><br>
                         <div class="inputBox">
                    <input type="text" name="endereco" id="endereco" class="inputUser" value="<?php echo $endereco; ?>" >
                    <label for="endereco" class="labelInput">Rua</label>
                </div>
                <br><br>
                
                <div class="inputBox">
                    <input type="text" name="numero" id="numero" class="inputUser" value="<?php echo $numero; ?>" >
                    <label for="numero" class="labelInput">Numero</label>
                </div>
                <br><br>    
               <div class="inputBox">
                    <input type="text" name="complemento" id="complemento" class="inputUser" value="<?php echo $complemento; ?>" >
                    <label for="complemento" class="labelInput">Complemento</label>
                </div>
                <br><br>
                <div class="inputBox">
                    <label for="tipo_conta">Tipo Conta: </label>
                    <select name="tipo_conta" required>
                        <option value="user" <?php echo ($tipo_conta == 'user') ? 'selected' : ''; ?>>Usuário</option>
                        <option value="admin" <?php echo ($tipo_conta == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="financeiro" <?php echo ($tipo_conta == 'financeiro') ? 'selected' : '' ?>>Financeiro</option>
                    </select>
                </div>
                <br><br>
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="submit" name="update" id="update" value="Atualizar">
                <a href="cadastros.php">Voltar</a>
            </fieldset>
        </form>
    </div>
</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const cepInput = documento.getElementById(cep);
        cepInput.addEventListener('blur', function(){
            const cep = this.value.replace(/\D/g, '');
            if (cep.lenght === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('endereco').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;
                    } else{
                        alert('CEP não encontrado.');
                    }
                })
                .catch(() => alert('Erro ao buscar CEP.'));
            }
        });
    });
function formatarCPF(campo) {
    campo.value = campo.value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
}

function formatarTelefone(campo) {
    campo.value = campo.value.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
}

function formatarCEP(campo){
    campo.value = campo.value.replace(/\D/g, '');

    campo.value = campo.value.substring(0, 8);

    if (campo.value.lenght > 5) {
        campo.value = campo.value.replace(/^(\d{5})(\d{1,3})$/, "$1-$2");
    }
}
</script>