<?php
session_start();
require '../ScreenCadastro/src/Exception.php';
require '../ScreenCadastro/src/PHPMailer.php';
require '../ScreenCadastro/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../ScreenCadastro/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailAtual = $_SESSION['usuario']['email'];
    $novoEmail = $_POST['email']; // Novo email enviado via POST
    $token = bin2hex(random_bytes(32)); // Gera um token

    $stmt = $conexao->prepare("UPDATE usuarios SET reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $token, $emailAtual);

    if ($stmt->execute()) {
        $link = "http://qxback.com.br/ScreenUser/confirmar_email.php?token=$token&email=$novoEmail";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rodrigo.teste0104@gmail.com'; 
            $mail->Password = 'fckh pnvn rqhv tpuk'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

$mail->setFrom('rodrigo.teste0104@gmail.com', 'Rodrigo');
$mail->addAddress($emailAtual); // Envia para o email atual
$logoPath = 'http://qxback.com.br/imagens/logobranca1.png';
$mail->isHTML(true);
$mail->CharSet = 'UTF-8';
$mail->Subject = 'Confirmação de Troca de Email';
$mail->Body = '
<div style="font-family: \'Montserrat\', sans-serif; background-color: #000000; color: #ffffff; padding: 20px; text-align: center;">
    <div style="background-color: #1a1a1a; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1); display: inline-block;">
        <img src="' . $logoPath . '" alt="Logo" style="max-width: 150px; height: auto; margin: 20px 0;">
        <h1 style="color: #44ff00; font-size: 24px; margin: 10px 0;">Confirmação de Troca de Email</h1>
        <p style="font-size: 16px; margin: 10px 0;">Você solicitou a troca de email para: <strong>' . htmlspecialchars($novoEmail) . '</strong>.</p>
        <p style="font-size: 16px; margin: 10px 0;">Para confirmar essa troca, clique no link abaixo:</p>
        <p>
            <a href="' . $link . '" style="text-decoration: none; color: #000000; background-color: #44ff00; padding: 10px 20px; border-radius: 5px; font-weight: bold;">Confirmar Troca de Email</a>
        </p>
        <p style="font-size: 16px; margin: 10px 0;">Se você não solicitou essa mudança, pode ignorar este e-mail.</p>
        <p style="font-size: 16px; margin: 10px 0;">Agradecemos a sua escolha!</p>
    </div>
</div>
';

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'E-mail de confirmação enviado com sucesso!']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao enviar o e-mail: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o token no banco de dados.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido.']);
}
?>
