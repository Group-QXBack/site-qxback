<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET'); 
header('Access-Control-Allow-Headers: Content-Type'); 

$apiUrl = 'https://brasilapi.com.br/api/banks/v1/';
$response = file_get_contents($apiUrl);

if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar dados']);
    exit();
}

echo $response;
?>
