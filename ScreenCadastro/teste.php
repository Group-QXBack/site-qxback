<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rodrigo.teste0104@gmail.com';
    $mail->Password = 'fckh pnvn rqhv tpuk'; // Verifique se é a senha correta ou senha de aplicativo
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Adicionando o protocolo de segurança
    $mail->Port = 587;

    $mail->setFrom('rodrigo.teste0104@gmail.com');
    $mail->addAddress('bianca.leitao@grupoqmax.com.br'); // Corrigido

    $mail->isHTML(true);
    $mail->Subject = 'Teste de email via gmail';
    $mail->Body = 'Chegou o email teste do <strong>QxBack</strong>';
    $mail->AltBody = 'Chegou o email teste do QxBack';

    if ($mail->send()) {
        echo 'Email enviado com sucesso';
    } else {
        echo 'Email não enviado';
    }
} catch (Exception $e) {
    echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
}
?>
