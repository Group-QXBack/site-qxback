<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'user') {
    header("Location: ../ScreenUser/index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$errors = [];
$url = "https://olinda.bcb.gov.br/olinda/servico/CCR/versao/v1/odata/InstituicoesFinanceirasAutorizadas?\$top=100&\$format=json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    $errors[] = 'Erro ao buscar instituições financeiras: ' . curl_error($ch);
} else {
    $data = json_decode($response, true);
    if (isset($data['value']) && is_array($data['value'])) {
        $instituicoes = $data['value'];
    } else {
        $errors[] = 'Nenhum dado encontrado na API.';
    }
}
curl_close($ch);

require_once '../ScreenCadastro/config.php';
$stmt = $conexao->prepare("SELECT * FROM contas_bancarias WHERE usuario_id = ?");
$stmt->bind_param('i', $usuario['id']);
$stmt->execute();
$result = $stmt->get_result();
$contas_bancarias = $result->fetch_assoc();
$stmt = $conexao->prepare("SELECT * FROM chaves_pix WHERE usuario_id = ?");
$stmt->bind_param('i', $usuario['id']);
$stmt->execute();
$result = $stmt->get_result();
$chaves_pix = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $banco = $_POST['banco'] ?? '';
    $conta = $_POST['conta'] ?? '';
    $agencia = $_POST['agencia'] ?? '';
    $tipo_chave = $_POST['tipo_chave'] ?? '';
    $chave_pix = $_POST['chave_pix'] ?? '';

    if (empty($errors)) {
        $stmt = $conexao->prepare("
            INSERT INTO contas_bancarias (usuario_id, banco, conta, agencia)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                banco = VALUES(banco),
                conta = VALUES(conta),
                agencia = VALUES(agencia)
        ");
        $stmt->bind_param('isssss', $usuario['id'], $banco, $conta, $agencia);

        if ($stmt->execute()) {
            $stmt = $conexao->prepare("
                INSERT INTO chaves_pix (usuario_id, tipo_chave, chave)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    tipo_chave = VALUES(tipo_chave),
                    chave = VALUES(chave)
            ");
            $stmt->bind_param('iss', $usuario['id'], $tipo_chave, $chave_pix);
            if ($stmt->execute()) {
                $success_message = 'Dados bancários cadastrados com sucesso!';
            } else {
                $errors[] = 'Erro ao atualizar a chave Pix. Tente novamente.';
            }
        } else {
            $errors[] = 'Ocorreu um erro ao atualizar seus dados bancários. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../ScreenUser/styleBancario.php">
    <link rel="shortcut icon" href="img/icon_uu.webp" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/af6c14a78e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Cadastro de Dados Bancários</title>
</head>

<body>
    <header>
        <img src="../imagens/logobranca1.png" class="logo" alt="logo">
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
            <li class= "item-menu">
            <a href="solicitar_resgate.php">
                <span class="icon"><i class="bi bi-coin"></i></i></span>
                <span class="txt-link">Resgatar</span>
        </a>
            </li>
            <li class="item-menu">
                <a href="logout.php">
                    <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="txt-link">Sair</span>
                </a>
            </li>
        </ul>
    </nav>
    </header>
    <div class="content">
        <form method="POST" action="">
            <div class="getReed">
                <h3>Editar Conta Bancaria</h3>
                <div class="getReed-Regras">
                    <span class="getReed-icons">
                    <i class="bi bi-lock"></i>
                    Fique tranquilo, não teremos acesso às suas transações bancárias, 
                    pedimos só o necessário para efetuar a transferência.
                    </span>
                    <span class="getReed-icons">
                    <i class="bi bi-info-circle"></i>
                    Ao preencher os dados bancários, certifique-se de que o CPF do titular da conta seja: 
                    </span>
                    <span class="getReed-icons">
                    <i class="bi bi-check-lg"></i>
                    A conta bancária indicada deve ser apta a receber transferência via PIX.
                    </span>
                </div>
            </div>
            <fieldset>
                <div class="row">
                    <label for="banco">Banco</label>
                    <select id="banco" name="banco" class="select2">
                        <option value="">Selecione um banco</option>
                        <?php foreach ($instituicoes as $instituicao): ?>
                            <option value="<?php echo htmlspecialchars($instituicao['Nome']); ?>" 
                                <?php echo (isset($contas_bancarias['banco']) && $contas_bancarias['banco'] === $instituicao['Nome']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($instituicao['Nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <label for="agencia">Agência</label>
                    <input type="number" id="agencia" name="agencia" value="<?php echo htmlspecialchars($contas_bancarias['agencia'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="conta">Conta</label>
                    <input type="number" id="conta" name="conta" value="<?php echo htmlspecialchars($contas_bancarias['conta'] ?? ''); ?>">
                </div>
                <div class="row">
                    <label for="tipo_chave">Tipo de Chave Pix</label>
                    <select id="tipo_chave" name="tipo_chave">
                        <option value="">Selecione um tipo de chave</option>
                        <option value="cpf" <?php echo ($chaves_pix['tipo_chave'] === 'cpf') ? 'selected' : ''; ?>>CPF</option>
                        <option value="telefone" <?php echo ($chaves_pix['tipo_chave'] === 'telefone') ? 'selected' : ''; ?>>Celular</option>
                        <option value="email" <?php echo ($chaves_pix['tipo_chave'] === 'email') ? 'selected' : ''; ?>>Email</option>
                        <option value="aleatoria" <?php echo ($chaves_pix['tipo_chave'] === 'aleatoria') ? 'selected' : ''; ?>>Chave Aleatória</option>
                    </select>
                </div>
                <div class="row" id="campo_chave" style="display: <?php echo ($chaves_pix) ? 'block' : 'none'; ?>;">
                    <label for="chave_pix">Chave Pix</label>
                    <input type="text" id="chave_pix" name="chave_pix" value="<?php echo htmlspecialchars($chaves_pix['chave'] ?? ''); ?>">
                </div>
            </fieldset>

            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php elseif (isset($success_message)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <div class="buttons">
                <button type="submit" class="btn-form">Enviar</button>
                <button class="btn-form" style="background-color: #ededed;"><a href="index.php">Voltar</a></button></button> 
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });

        document.getElementById('tipo_chave').addEventListener('change', function() {
            const campoChave = document.getElementById('campo_chave');
            campoChave.style.display = this.value ? 'block' : 'none';
        });
        function formatarCPF(campo) {
            campo.value = campo.value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        }
    </script>
</body>
</html>
