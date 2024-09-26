<?php
if (isset($_GET['cnpj'])) {
    $cnpj = preg_replace('/\D/', '', $_GET['cnpj']); 

    $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    header('Content-Type: application/json');
    echo $response;
}
?>
