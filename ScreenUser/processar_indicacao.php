<?php
session_start();
include '../ScreenCadastro/config.php'; 

if (!isset($_SESSION['usuario'])) {
    header("Location: ../ScreenUser/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitização e validação dos dados
    $nome_empresa = $conexao->real_escape_string(trim($_POST['nome_empresa']));
    $cnpj = $conexao->real_escape_string(trim($_POST['cnpj']));
    $cpf = $conexao->real_escape_string(trim($_POST['cpf']));
    $telefone_empresa = $conexao->real_escape_string(trim($_POST['telefone_empresa']));
    $celular_empresa = $conexao->real_escape_string(trim($_POST['celular_empresa']));
    $email_empresa = $conexao->real_escape_string(trim($_POST['email_empresa']));
    $nome_contato = $conexao->real_escape_string(trim($_POST['nome_contato']));
    $cargo_contato = $conexao->real_escape_string(trim($_POST['cargo_contato']));
    $celular_contato = $conexao->real_escape_string(trim($_POST['celular_contato']));
    $email_contato = $conexao->real_escape_string(trim($_POST['email_contato']));
    
    $areasSelecionadas = isset($_POST['areas']) ? $_POST['areas'] : [];

    if (empty($nome_empresa) || empty($cnpj) || empty($cpf) || empty($celular_empresa) || empty($email_empresa) || empty($nome_contato) || empty($celular_contato) || empty($email_contato)) {
        header("Location: ../ScreenUser/indicar.php?error=Todos os campos obrigatórios devem ser preenchidos!");
        exit();
    }

    $usuario_id = $_SESSION['usuario']['id']; // Obtém o ID do usuário conectado

    $conexao->begin_transaction();

    try {
        // Inserção na tabela de indicações
        $stmt = $conexao->prepare("INSERT INTO indicacoes (nome_empresa, cnpj, cpf, telefone_empresa, celular_empresa, email_empresa, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssi', $nome_empresa, $cnpj, $cpf, $telefone_empresa, $celular_empresa, $email_empresa, $usuario_id);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao inserir indicação: " . $conexao->error);
        }
        $indicacao_id = $stmt->insert_id; // Obtém o ID da última inserção

        // Inserção na tabela de contatos
        $stmt = $conexao->prepare("INSERT INTO contatos (indicacao_id, nome_contato, cargo_contato, celular_contato, email_contato) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('issss', $indicacao_id, $nome_contato, $cargo_contato, $celular_contato, $email_contato);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao inserir contato: " . $conexao->error);
        }

        // Insere as áreas associadas
        if (!empty($areasSelecionadas)) {
            $stmt = $conexao->prepare("INSERT INTO indicacoes_areas (indicacao_id, area_id) VALUES (?, ?)");

            foreach ($areasSelecionadas as $area_id) {
                if (!empty($area_id)) {
                    $stmt->bind_param('ii', $indicacao_id, $area_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Erro ao inserir área: " . $conexao->error);
                    }
                }
            }
        }

        $conexao->commit();
        header("Location: ../ScreenUser/indicar.php?success=Indicação enviada com sucesso!");
    } catch (Exception $e) {
        // Em caso de erro, desfaz a transação
        $conexao->rollback();
        header("Location: ../ScreenUser/indicar.php?error=" . urlencode($e->getMessage()));
    } finally {
        $stmt->close();
        $conexao->close();
    }
} else {
    header("Location: ../ScreenUser/indicar.php");
}
?>
