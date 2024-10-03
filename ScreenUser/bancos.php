<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET'); 
header('Access-Control-Allow-Headers: Content-Type'); 

$apiUrl = 'https://olinda.bcb.gov.br/olinda/servico/CCR/versao/v1/odata/InstituicoesFinanceirasAutorizadas?$top=100&$format=json';
$response = file_get_contents($apiUrl);

if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar dados']);
    exit();
}

echo $response;
?>
