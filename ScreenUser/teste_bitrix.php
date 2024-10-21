<?php
$webhookUrl = 'https://targetcontabil.bitrix24.com.br/rest/309/9d86lu9uagrawpyj/profile.json?NEW_PARAM=crm.lead.add';

$ch = curl_init($webhookUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([])); 
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Erro na conexão: ' . curl_error($ch);
} else {
    echo 'Resposta do servidor: ' . $response;
}

curl_close($ch);
?>
