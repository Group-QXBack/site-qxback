<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$url = 'https://www3.bcb.gov.br/sgspub/localizarseries/localizarSeries.do?method=prepararTelaLocalizarSeries';
$response = file_get_contents($url);
echo $response;
?>
    