<?php
$bitrix24Url = 'https://targetcontabil.bitrix24.com.br/rest/311';
$accessToken = 'otqvjvwp0l4r0y4n';
 
$ch = curl_init();
 
curl_setopt($ch, CURLOPT_URL, $bitrix24Url . '?access_token=' . $accessToken);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
 
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Erro: ' . curl_error($ch);
} else {
    $data = json_decode($response, true);
 
    if (isset($data['result'])) {
        echo 'Conexão bem-sucedida! Dados recebidos: ';
        print_r($data['result']);
    } else {
        echo 'Erro ao conectar: ' . (isset($data['error_description']) ? $data['error_description'] : 'Descrição do erro não disponível.');
    }
}
 
curl_close($ch);
?>