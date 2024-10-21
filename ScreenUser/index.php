<?php
session_start();
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.php");
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
    <link rel="stylesheet" href="../ScreenUser/style.php">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Perfil</title>
</head>

<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="logo">
        <nav class="menu-lateral">
            <div class="btn-expandir">
                <i class="bi bi-list"></i>
            </div>
            <ul>
                <li class="item-menu"><a href="index.php"><span class="icon"><i class="bi bi-person-fill"></i></span><span class="txt-link">Perfil</span></a></li>
                <li class="item-menu"><a href="../ScreenUser/minhas_indicacoes.php"><span class="icon"><i class="bi bi-journal-plus"></i></span><span class="txt-link">Minhas Indicações</span></a></li>
                <li class="item-menu"><a href="../ScreenUser/indicarUsuario.php"><span class="icon"><i class="bi bi-plus-square"></i></span><span class="txt-link">Indicar</span></a></li>
                <li class="item-menu"><a href="solicitar_resgate.php"><span class="icon"><i class="bi bi-coin"></i></span><span class="txt-link">Resgatar</span></a></li>
                <li class="item-menu"><a href="logout.php"><span class="icon"><i class="bi bi-box-arrow-right"></i></span><span class="txt-link">Sair</span></a></li>
            </ul>
        </nav>
    </header>
    <section>
        <div class="primeira_sessao">
            <div class="profile-page">
                <div class="dados-perfil">
                    <h1 style="font-size: 30px; margin-bottom: 2px;">Detalhes de Login</h1>
                    <p class="grid" style="margin-bottom: -10px;">
                        <strong class="block__item">Email</strong>
                        <input type="text" class="input-text" style="width: 100%;" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" readonly>
                        <button id="openDialogEmail"><i class="bi bi-pencil"></i></button>
                        <dialog id="dialog-email" style="height: 340px;">
                            <div class="containeremail">
                                <h1>Alterar Email</h1>
                                <p style="margin-bottom: 20px;">Insira seu novo email e enviaremos um link de confirmação para <span style="font-weight: bold;"><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></span>, validando a alteração</p>
                                <input type="email" class="input-dialog-email" placeholder="Novo email" required>
                                <button id="saveEmail" style="background-color: chartreuse; font-weight: 500; margin-top: 10px;">Enviar</button>
                                <button id="closeDialogEmail" style="color: #31b800; font-weight: 500;">Cancelar</button>
                                <div id="feedback" style="margin-top: 10px;"></div>
                            </div>
                        </dialog>
                        <style>
                            .containeremail {
                                display: flex;
                                flex-direction: column;
                                padding: 20px;
                            }

                            .containeremail .input-dialog-email {
                                width: 100%;
                                height: 35px;
                                border-radius: 10px;
                                margin-bottom: 10px;
                            }

                            button {
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                height: 40px;
                                border-radius: 10px;
                                justify-content: center;
                            }

                            .containeremail h1 {
                                font-weight: bold;
                                font-size: 25px;
                                margin-bottom: 10px;
                            }

                            .containeremail p,
                            strong {
                                font-size: 17px;
                                color: #545454;
                            }

                            .containeremail .input-dialog-email {
                                height: 35px;
                                border-radius: 10px;
                            }
                        </style>
                    </p>
                    <p class="grid" style="margin-bottom: -10px;">
                        <strong class="block__item">Senha</strong>
                        <input type="password" class="input-text" style="width: 100%;" value="<?php echo htmlspecialchars($usuario['senha'] ?? ''); ?>">
                        <button id="openDialog">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <dialog id="dialog">
                            <div class="container">
                                <h1>Alterar Senha</h1>
                                <p style="margin-bottom: 55px;">
                                    Enviaremos um e-mail para <span style="font-weight: bold;"><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></span> com o link para alterar sua senha.
                                </p>
                                <style>
                                    .container {
                                        display: flex;
                                        flex-direction: column;
                                    }

                                    .container .input-dialog {
                                        width: 100%;
                                    }

                                    button#sendEmail,
                                    #closeDialog {
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        height: 40px;
                                        border-radius: 10px;
                                        justify-content: center;
                                    }

                                    .container h1 {
                                        font-weight: bold;
                                        font-size: 25px;
                                        margin-bottom: 20px;
                                    }

                                    .container p,
                                    strong {
                                        font-size: 17px;
                                        color: #545454;
                                    }

                                    .container .input-dialog {
                                        height: 35px;
                                        border-radius: 10px;
                                    }
                                </style>
                                <button id="sendEmail" style="background-color: chartreuse; font-weight: 500;">Enviar E-mail</button>
                                <button id="closeDialog" style="color: #31b800; font-weight: 500;">Cancelar</button>
                                <div id="feedback" style="margin-top: 10px;"></div>
                            </div>
                        </dialog>
                    </p>

                    <script>
                        document.getElementById('sendEmail').addEventListener('click', function() {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', 'enviar_email.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === XMLHttpRequest.DONE) {
                                    const response = JSON.parse(xhr.responseText);
                                    const feedback = document.getElementById('feedback');
                                    feedback.innerHTML = response.message;
                                    feedback.style.color = response.status === 'success' ? 'green' : 'red';
                                    if (response.status === 'success') {
                                        document.getElementById('dialog').close();
                                    }
                                }
                            };

                            xhr.send();
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === XMLHttpRequest.DONE) {
                                    const response = JSON.parse(xhr.responseText);
                                    const feedback = document.getElementById('feedback');
                                    feedback.innerHTML = response.message;
                                    feedback.style.color = response.status === 'success' ? 'green' : 'red';
                                    if (response.status === 'success') {
                                        document.getElementById('dialog').close();
                                    }
                                }
                            };

                            xhr.send();
                        });
                    </script>
                    <h2 style="font-size: 30px; margin-bottom: 2px;">Informações pessoais</h2>
                    <p class="grid" style="margin-bottom: 5px;">
                        <strong class="block__section">Nome</strong>
                        <input type="text" class="input-text" style="width: 100%;"
                            value="<?php echo htmlspecialchars(trim($usuario['nome'] . ' ' . ($usuario['Sobrenome'] ?? ''))); ?>"
                            readonly>
                        <button id="openDialogNome">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <dialog id="dialogNome">
                            <div class="container">
                                <form method="POST" action="processar_usuario.php">
                                    <h1>Qual o seu nome?</h1>
                                    <strong>Nome</strong>
                                    <input class="input-dialog" type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" required>

                                    <strong>Sobrenome</strong>
                                    <input class="input-dialog" type="text" id="sobrenome" name="sobrenome" value="<?php echo htmlspecialchars($usuario['Sobrenome'] ?? ''); ?>" required>
                                    <button type="submit" id="saveNome" style="background-color: chartreuse; font-weight: 500; margin-top: 13px;">Salvar</button>
                                    <button type="button" id="closeDialogNome" style="color: #31b800; font-weight: 500;" onclick="document.getElementById('dialogNome').close();">Cancelar</button>
                                </form>
                            </div>
                        </dialog>
                        <script>
                            document.getElementById('openDialogNome').addEventListener('click', function() {
                                document.getElementById('dialogNome').showModal();
                            });
                        </script>
                        <style>
                            button#saveNome,
                            #closeDialogNome {
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                height: 40px;
                                border-radius: 10px;
                                justify-content: center;
                            }
                        </style>
                        </dialog>
                    <p class="grid" style="margin-bottom: -10px;">
                        <strong class="block__section">CPF</strong>
                        <input type="text" class="input-text" style="width: 100%;" value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>" readonly>
                        <button id="openDialogCPF">
                            <i class="bi bi-pencil"></i>
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
                                button#saveCPF,
                                #closeDialogCPF {
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
                    <p class="grid" style="margin-bottom: -10px;">
                        <strong class="block__section">Nascimento</strong>
                        <input type="date" class="input-date" style="width: 120%;" value="<?php echo htmlspecialchars($usuario['data_nasc'] ?? ''); ?>" readonly>
                        <button id="openDialogNascimento">
                            <i class="bi bi-pencil"></i>
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
                                button#saveNascimento,
                                #closeDialogNascimento {
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
                    <p class="grid" style="margin-bottom: -10px;">
                        <strong class="block__section">Telefone</strong>
                        <input type="text" class="input-text" style="width: 100%;" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" readonly>
                        <button id="openDialogTelefone">
                            <i class="bi bi-pencil"></i>
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
                                button#saveTelefone,
                                #closeDialogTelefone {
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
                </div>

    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dialogs = {
                senha: document.getElementById('dialog'),
                email: document.getElementById('dialog-email'), // ID corrigido
                nome: document.getElementById('dialogNome'),
                cpf: document.getElementById('dialogCPF'),
                nascimento: document.getElementById('dialogNascimento'),
                telefone: document.getElementById('dialogTelefone'),
            };
            const openDialogButtons = {
                senha: document.getElementById('openDialog'),
                email: document.getElementById('openDialogEmail'),
                nome: document.getElementById('openDialogNome'),
                cpf: document.getElementById('openDialogCPF'),
                nascimento: document.getElementById('openDialogNascimento'),
                telefone: document.getElementById('openDialogTelefone'),
            };

            const closeDialogButtons = {
                senha: document.getElementById('closeDialog'),
                email: document.getElementById('closeDialogEmail'),
                nome: document.getElementById('closeDialogNome'),
                cpf: document.getElementById('closeDialogCPF'),
                nascimento: document.getElementById('closeDialogNascimento'),
                telefone: document.getElementById('closeDialogTelefone'),
            };

            Object.keys(openDialogButtons).forEach(key => {
                openDialogButtons[key].addEventListener('click', function() {
                    dialogs[key].showModal();
                });
            });

            Object.keys(closeDialogButtons).forEach(key => {
                closeDialogButtons[key].addEventListener('click', function() {
                    dialogs[key].close();
                });
            });

            document.getElementById('saveEmail').addEventListener('click', function() {
                const newEmail = document.querySelector('#dialog-email input[type="email"]').value;
                if (newEmail) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'atualizar_email.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            const response = JSON.parse(xhr.responseText);
                            const feedback = document.getElementById('feedback');
                            feedback.innerHTML = response.message;
                            feedback.style.color = response.status === 'success' ? 'green' : 'red';

                            if (response.status === 'success') {
                                document.getElementById('dialog-email').close();
                            }
                        }
                    };

                    xhr.send(`email=${encodeURIComponent(newEmail)}`);
                } else {
                    alert('Por favor, insira um novo email.');
                }
            });

            document.getElementById('saveTelefone').addEventListener('click', function() {
                const telefone = document.querySelector('#dialogTelefone input[type="text"]').value;
                alert(`Telefone atualizado para: ${telefone}`);
                dialogs['telefone'].close();
            });
        });
    </script>
</body>

</html>