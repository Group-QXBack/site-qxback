<?php
session_start();
include '../ScreenCadastro/config.php'; 

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] !== 'user') {
    header("Location: ../ScreenUser/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_empresa = $conexao->real_escape_string(trim($_POST['nome_empresa']));
    $cnpj = $conexao->real_escape_string(trim($_POST['cnpj']));
    $nome_contato = $conexao->real_escape_string(trim($_POST['nome_contato']));
    $cargo_contato = $conexao->real_escape_string(trim($_POST['cargo_contato']));
    $numero_contato = $conexao->real_escape_string(trim($_POST['numero_contato']));
    $email_contato = $conexao->real_escape_string(trim($_POST['email_contato']));
    $servicosSelecionados = isset($_POST['servicos']) ? $_POST['servicos'] : [];

    if (empty($nome_empresa) || empty($cnpj) || empty($nome_contato) || empty($numero_contato) || empty($email_contato)) {
        header("Location: ../ScreenUser/indicar.php?error=Todos os campos obrigatórios devem ser preenchidos!");
        exit();
    }

    $usuario_id = $_SESSION['usuario']['id']; 
    $conexao->begin_transaction();

    try {
        $stmt = $conexao->prepare("INSERT INTO indicacoes (nome_empresa, cnpj, usuario_id) VALUES (?, ?, ?)");   
        if (!$stmt) {
            throw new Exception("Erro na preparação do SQL: " . $conexao->error);
        }
        $stmt->bind_param('sss', $nome_empresa, $cnpj, $usuario_id);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao inserir indicação: " . $stmt->error);
        }
        $indicacao_id = $stmt->insert_id; 
        $stmt->close();

        $stmt = $conexao->prepare("INSERT INTO contatos (indicacao_id, nome_contato, cargo_contato, numero_contato, email_contato) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Erro na preparação do SQL: " . $conexao->error);
        }
        $stmt->bind_param('issss', $indicacao_id, $nome_contato, $cargo_contato, $numero_contato, $email_contato);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao inserir contato: " . $stmt->error);
        }
        $stmt->close();

        if (!empty($servicosSelecionados)) {
            $stmt = $conexao->prepare("INSERT INTO indicacoes_servicos (indicacao_id, servicos_id) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Erro na preparação do SQL: " . $conexao->error);
            }

            foreach ($servicosSelecionados as $servicos_id) {
                if (!empty($servicos_id)) {
                    $stmt->bind_param('ii', $indicacao_id, $servicos_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Erro ao inserir serviço: " . $stmt->error);
                    }
                }
            }
            $stmt->close();
        }

        $conexao->commit();
        header("Location: ../ScreenUser/indicar.php?success=Indicação enviada com sucesso!");
        exit();
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: ../ScreenUser/indicar.php?error=" . urlencode($e->getMessage()));
        exit();
    } finally {
        $conexao->close();
    }
} else {
    header("Location: ../ScreenUser/indicar.php");
    exit();
}

