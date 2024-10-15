<?php
session_start();
require '../ScreenCadastro/src/Exception.php';
require '../ScreenCadastro/src/PHPMailer.php';
require '../ScreenCadastro/src/SMTP.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../ScreenCadastro/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['usuario']['email'];
    $token = bin2hex(random_bytes(32));
    $link = "http://qxback.com.br/ScreenUser/trocar_senha.php?token=$token";

    $stmt = $conexao->prepare("UPDATE usuarios SET reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $token, $email);
    
    if ($stmt->execute()) {
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
            $mail->addAddress($email);
            $logoPath = 'http://qxback.com.br/imagens/logobranca1.png';
            $mail->isHTML(true);
            $mail->Subject = 'Redefinir Senha';
            $mail->Body = '
            <div style="font-family: \'Montserrat\', sans-serif; background-color: #f4f4f4; color: #333; padding: 20px; text-align: center;">
                <img src="' . $logoPath . '" alt="Logo" style="max-width: 200px; margin: 20px 0;">
                <h1 style="color: #44ff00;">Redefinição de Senha</h1>
                <p>Recebemos um pedido para redefinir sua senha. Para continuar, clique no link abaixo:</p>
                <p>
                    <a href="' . $link . '" style="text-decoration: none; color: #44ff00; font-weight: bold; border: 2px solid #44ff00; padding: 10px 20px; border-radius: 5px;">Redefinir Senha</a>
                </p>
                <p>Se voce nao solicitou a troca de senha, pode ignorar este e-mail.</p>
                <p>Agradecemos a sua escolha!</p>
            </div>
            ';
            

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'E-mail enviado com sucesso!']);
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
