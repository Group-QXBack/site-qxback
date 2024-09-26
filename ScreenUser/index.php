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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ScreenUser/style.php ">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Perfil</title>
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
                <a href="perfil.html">
                    <span class="icon"><i class="bi bi-person-fill"></i></span>
                    <span class="txt-link">Perfil</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="">
                    <span class="icon"><i class="bi bi-journal-plus"></i></span>
                    <span class="txt-link">Minhas Indicações</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="./index.html">
                    <span class="icon"><i class="bi bi-plus-square"></i></span>
                    <span class="txt-link">Indicar</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="">
                    <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="txt-link">Sair</span>
                </a>
            </li>
        </ul>
    </nav>
    <section>
        <div class="primeira_sessao">
            <div class="profile-page">
                <div class="dados-perfil">
                    <h1 style="font-size: 30px; margin-bottom: 10px;">Detalhes de Login</h1>
        
                    <p class="grid">
                        <strong class="block__item">Email</strong>
                        <input type="text" class="input-text" style="width: 235px;" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" readonly>
                        <button id="openDialogEmail">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialog-email">
                            <div class="container">
                                <h1>Alterar Email</h1>
                                <input type="email" class="input-dialog" placeholder="Novo email">
                                <button id="saveEmail">Salvar</button>
                                <button id="closeDialogEmail">Cancelar</button>
                            </div>
                        </dialog>
                    </p>
        
                    <p class="grid">
                        <strong class="block__item">Senha</strong>
                        <input type="text" class="input-text" style="width: 100px;">
                        <button id="openDialog">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialog">
                            <div class="container">
                                <h1>Alterar Senha</h1>
                                <p style="margin-bottom: 55px;">Enviaremos um e-mail para <span style="font-weight: bold;"><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></span> com o link para alterar sua senha.</p>
                                <style>
                                .container{
                                    display: flex;
                                    flex-direction: column;
                                }
                                .container .input-dialog{
                                    width: 100%;
                                }
                                button#sendEmail,#closeDialog{
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        height: 40px;
                                        border-radius: 10px;
                                        justify-content: center;
                                }
                                .container h1{
                                    font-weight: bold;
                                    font-size: 25px;
                                    margin-bottom: 20px;
                                }
                                .container p, strong{
                                    font-size: 17px;
                                    color: #545454;
                                }
                                .container .input-dialog{
                                    height: 35px;
                                    border-radius: 10px;
                                }
                                </style>
                                <button id="sendEmail" style="background-color: chartreuse; font-weight: 500;">Enviar E-mail</button>
                                <button id="closeDialog" style="color: #31b800; font-weight: 500;">Cancelar</button>
                            </div>
                        </dialog>
        
                    <h2 style="font-size: 30px; margin-bottom: 10px;">Informações pessoais</h2>

                    <p class="grid">
                        <strong class="block__section">Nome</strong>
                        <input type="text" class="input-text" style="width: 100px;" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" readonly>
                        <button id="openDialogNome">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialogNome">
                            <div class="container">
                                <h1>Qual o seu nome?</h1>
                                <strong>Nome</strong>
                                <input class="input-dialog" type="text">
                                <strong>Sobrenome</strong>
                                <input class="input-dialog" type="text">
                                <button id="saveNome" style="background-color: chartreuse; font-weight: 500; margin-top: 13px;">Salvar</button>
                                <button id="closeDialogNome" style="color: #31b800; font-weight: 500;">Cancelar</button>
                            </div>
                            <style>
                                button#saveNome,#closeDialogNome{
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    height: 40px;
                                    border-radius: 10px;
                                    justify-content: center;
                                }
                            </style>
                        </dialog>
                    </>
        
                    <p class="grid">
                        <strong class="block__section">CPF</strong>
                        <input type="text" class="input-text" style="width: 100px;" value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>" readonly>
                        <button id="openDialogCPF">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialogCPF">
                            <div class="container">
                                <h1>Qual o seu CPF?</h1>
                                <p>Digite um CPF legítimo e que pertença a você.<br>Não será possível alterar seu CPF.</p>
                                <strong style="margin-top: 20px; font-size: 15px; color: #000;">CPF</strong>
                                <input type="text" class="input-dialog" value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>" readonly>
                                <button id="saveCPF" type="submit" style="background-color: chartreuse; font-weight: 500; margin-top: 13px;">Salvar</button>
                                <button id="closeDialogCPF" style="color: #31b800; font-weight: 500;">Cancelar</button>
                            </div>
                            <style>
                                button#saveCPF,#closeDialogCPF{
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    height: 40px;
                                    border-radius: 10px;
                                    justify-content: center;
                                }
                            </style>
                        </dialog>
                    </p>
        
                    <p class="grid">
                        <strong class="block__section">Data de Nascimento</strong>
                        <input type="date" class="input-date" value="<?php echo htmlspecialchars($usuario['data_nasc'] ?? ''); ?>" readonly>
                        <button id="openDialogNascimento">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialogNascimento" style="height: 350px;">
                            <div class="container">
                                <h1>Qual é a sua data de nascimento?</h1>
                                <p>A data de nascimento é vinculada ao seu cadastro e não será possível alterá-la.</p>
                                <strong style="margin-top: 20px; font-size: 15px; color: #000;">Data de Nascimento</strong>
                                <input type="date" class="input-dialog" value="<?php echo htmlspecialchars($usuario['data_nasc'] ?? ''); ?>" readonly>
                                <button id="saveNascimento" type="submit" style="background-color: chartreuse; font-weight: 500; margin-top: 13px;">Salvar</button>
                                <button id="closeDialogNascimento" style="color: #31b800; font-weight: 500;">Cancelar</button>
                            </div>
                            <style>
                                button#saveNascimento,#closeDialogNascimento{
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    height: 40px;
                                    border-radius: 10px;
                                    justify-content: center;
                                }
                            </style>
                        </dialog>
                    </p>
        
                    <p class="grid">
                        <strong class="block__section">Telefone</strong>
                        <input type="text" class="input-text" style="width: 180px;" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" readonly>
                        <button id="openDialogTelefone">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialogTelefone" style="height: 280px;">
                            <div class="container">
                                <h1>Qual é o seu telefone?</h1>
                                <strong>Telefone</strong>
                                <input type="text" class="input-dialog" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" readonly>
                                <button id="saveTelefone" type="submit" style="background-color: chartreuse; font-weight: 500; margin-top: 13px;">Salvar</button>
                                <button id="closeDialogTelefone" style="color: #31b800; font-weight: 500;">Cancelar</button>
                            </div>
                            <style>
                                button#saveTelefone,#closeDialogTelefone{
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    height: 40px;
                                    border-radius: 10px;
                                    justify-content: center;
                                }
                            </style>
                        </dialog>
                    </p>
        
                    <p class="grid">
                        <strong class="block__section">CEP</strong>
                        <input type="text" class="input-text" style="width: 100%;" value="<?php echo htmlspecialchars($usuario['cep'] ?? ''); ?>" readonly>
                        <button id="openDialogCEP">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialogCEP">
                            <div class="container">
                                <h1>Alterar CEP</h1>
                                <input type="text" class="input-dialog" placeholder="Novo CEP">
                                <button id="saveCEP">Salvar</button>
                                <button id="closeDialogCEP">Cancelar</button>
                            </div>
                        </dialog>
                    </p>
        
                    <p class="grid">
                        <strong class="block__section">Endereço</strong>
                        <input type="text" class="input-text" style="width: 180px;" value="<?php echo htmlspecialchars($usuario['endereco'] ?? ''); ?>" readonly>
                        <button id="openDialogEndereco">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialogEndereco">
                            <div class="container">
                                <h1>Alterar Endereço</h1>
                                <input type="text" class="input-dialog" placeholder="Novo Endereço">
                                <button id="saveEndereco">Salvar</button>
                                <button id="closeDialogEndereco">Cancelar</button>
                            </div>
                        </dialog>
                    </p>
        
                    <p class="grid">
                        <strong class="block__section">Complemento</strong>
                        <input type="text" class="input-text" value="<?php echo htmlspecialchars($usuario['complemento'] ?? ''); ?>" readonly>
                        <button id="openDialogComplemento">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <dialog id="dialogComplemento">
                            <div class="container">
                                <h1>Alterar Complemento</h1>
                                <input type="text" class="input-dialog" placeholder="Novo Complemento">
                                <button id="saveComplemento">Salvar</button>
                                <button id="closeDialogComplemento">Cancelar</button>
                            </div>
                        </dialog>
                    </p>
                </div>
            </div>
        </div>        
        </section>
</body>
</html>
<script>
// todos os diálogos e botões
const dialogs = {
    senha: document.getElementById('dialog'),
    email: document.getElementById('dialogEmail'),
    nome: document.getElementById('dialogNome'),
    cpf: document.getElementById('dialogCPF'),
    nascimento: document.getElementById('dialogNascimento'),
    telefone: document.getElementById('dialogTelefone'),
    cep: document.getElementById('dialogCEP'),
    endereco: document.getElementById('dialogEndereco'),
    complemento: document.getElementById('dialogComplemento')
};

// Função para abrir os diálogos
const openDialogButtons = {
    senha: document.getElementById('openDialog'),
    email: document.getElementById('openDialogEmail'),
    nome: document.getElementById('openDialogNome'),
    cpf: document.getElementById('openDialogCPF'),
    nascimento: document.getElementById('openDialogNascimento'),
    telefone: document.getElementById('openDialogTelefone'),
    cep: document.getElementById('openDialogCEP'),
    endereco: document.getElementById('openDialogEndereco'),
    complemento: document.getElementById('openDialogComplemento')
};

// Função para fechar os diálogos
const closeDialogButtons = {
    senha: document.getElementById('closeDialog'),
    email: document.getElementById('closeDialogEmail'),
    nome: document.getElementById('closeDialogNome'),
    cpf: document.getElementById('closeDialogCPF'),
    nascimento: document.getElementById('closeDialogNascimento'),
    telefone: document.getElementById('closeDialogTelefone'),
    cep: document.getElementById('closeDialogCEP'),
    endereco: document.getElementById('closeDialogEndereco'),
    complemento: document.getElementById('closeDialogComplemento')
};

// abertura para cada botão
Object.keys(openDialogButtons).forEach(key => {
    openDialogButtons[key].addEventListener('click', function() {
        dialogs[key].showModal(); 
    });
});

// funçao de fechamento para cada botão "Cancelar"
Object.keys(closeDialogButtons).forEach(key => {
    closeDialogButtons[key].addEventListener('click', function() {
        dialogs[key].close(); 
    });
});

// Lógica para o botão "Enviar E-mail" (exemplo)
document.getElementById('sendEmail').addEventListener('click', function() {
    alert('E-mail enviado!');
    dialogs['senha'].close(); 
});

// não ser possivel alterar depois de preenchido
const input = document.getElementById('input-dialog');
input.addEventListener('input-dialog', function() {
if (this.value !== "") {
this.readOnly = true;
}
});

</script>