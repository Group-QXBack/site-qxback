<?php
if (isset($_GET['cep'])) {
    $cep = preg_replace('/\D/', '', $_GET['cep']); 

    $url = "https://viacep.com.br/ws/{$cep}/json/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    header('Content-Type: application/json');
    echo $response;
}
?>
