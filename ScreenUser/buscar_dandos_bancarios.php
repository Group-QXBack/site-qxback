<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'user') {
    echo json_encode(['success' => false, 'message' => 'Usuário não autorizado.']);
    exit();
}

require_once '../ScreenCadastro/config.php'; // Configurações do banco de dados

$errors = [];
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $telefone = $data['telefone'] ?? '';
    $cep = $data['cep'] ?? '';
    $endereco = $data['endereco'] ?? '';
    $numero = $data['numero'] ?? '';
    $complemento = $data['complemento'] ?? '';
    $bairro = $data['bairro'] ?? '';
    $cidade = $data['cidade'] ?? '';
    $estado = $data['estado'] ?? '';
    $nome_titular = $data['nome'] ?? '';
    $cpf_titular = $data['sobrenome'] ?? '';
    $banco = $data['email'] ?? '';
    $conta = $data['dtnasc'] ?? '';
    $agencia = $data['agencia'] ?? '';

    if (empty($telefone) || empty($cep) || empty($endereco) || empty($estado) || empty($complemento) || empty($cidade) || empty($bairro) || empty($nome_titular) || empty($cpf_titular) || empty($banco) || empty($conta) || empty($agencia)) {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf_titular)) {
        $errors[] = 'CPF inválido.';
    }

    if (empty($errors)) {
        $usuario_id = $_SESSION['usuario']['id'];
        $cpf_usuario = $_SESSION['usuario']['cpf'];

        $stmt = $conexao->prepare("UPDATE usuarios SET telefone=?, cep=?, endereco=?, numero=?, complemento=?, bairro=?, cidade=?, estado=? WHERE cpf=?");
        $stmt->bind_param('sssssssss', $telefone, $cep, $endereco, $numero, $complemento, $bairro, $cidade, $estado, $cpf_usuario);

        if ($stmt->execute()) {
            // Atualiza os dados bancários
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
            $stmt->bind_param('isssss', $usuario_id, $nome_titular, $cpf_titular, $banco, $conta, $agencia);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar dados bancários.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar dados pessoais.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => $errors]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método HTTP não permitido.']);
}
?>
