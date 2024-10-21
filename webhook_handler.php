<?php
header("Content-Type: application/json");

$data = file_get_contents("php://input");

$json_data = json_decode($data, true);

if ($json_data) {
    file_put_contents('webhook_log.txt', print_r($json_data, true), FILE_APPEND);
    echo json_encode(['status' => 'success', 'message' => 'Dados recebidos com sucesso!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum dado recebido']);
}
?>
