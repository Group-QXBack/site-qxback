<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_conta'] != 'admin') {
    echo "Acesso restrito.";
    exit();
}

include '../ScreenCadastro/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $solicitacao_id = isset($_POST['solicitacao_id']) ? intval($_POST['solicitacao_id']) : 0;
    $acao = isset($_POST['acao']) ? $_POST['acao'] : '';

    if ($solicitacao_id <= 0 || !in_array($acao, ['aceitar', 'negar'])) {
        echo "Dados inválidos.";
        exit();
    }

    $novo_status = '';
    $sql_update = '';

    if ($acao == 'aceitar') {
        $novo_status = 'Aceito';
        $sql_update = "UPDATE usuarios SET tipo_conta = 'user' WHERE id = (SELECT usuario_id FROM solicitacoes_inativacao WHERE id = ?)";
    } elseif ($acao == 'negar') {
        $novo_status = 'Negado';
    }

    $conexao->begin_transaction();

    try {
        $stmt = $conexao->prepare("UPDATE solicitacoes_inativacao SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_status, $solicitacao_id);
        $stmt->execute();
        $stmt->close();

        if ($acao == 'aceitar') {
            $stmt = $conexao->prepare($sql_update);
            $stmt->bind_param("i", $solicitacao_id);
            $stmt->execute();
            $stmt->close();
        }

        $conexao->commit();
        header("Location: solicitacoes_reativacao.php");
        exit();
    } catch (Exception $e) {
        $conexao->rollback();
        echo "Erro: " . $e->getMessage();
    }
}
$conexao->close();
?>
