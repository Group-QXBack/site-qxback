<?php
session_start();
include_once('config.php');
require_once('../ScreenCadastro/src/PHPMailer.php');
require_once('../ScreenCadastro/src/SMTP.php');
require_once('../ScreenCadastro/src/Exception.php');
include '../ScreenCadastro/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $conexao->real_escape_string($_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conexao->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($senha, $row['senha'])) {
                if (!$row['email_confirmado']) {
                    // Se o e-mail não foi confirmado, gere um novo token
                    $token = bin2hex(random_bytes(16)); 
                    $updateTokenSql = "UPDATE usuarios SET token=? WHERE email=?";
                    $stmt = $conexao->prepare($updateTokenSql);
                    $stmt->bind_param("ss", $token, $email);
                    $stmt->execute();

                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'rodrigo.teste0104@gmail.com';
                        $mail->Password = 'fckh pnvn rqhv tpuk';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('rodrigo.teste0104@gmail.com');
                        $mail->addAddress($email);
                        $logoPath = 'http://qxback.com.br/imagens/logobranca1.png';
                        $mail->isHTML(true);
                        $mail->Subject = 'Ativar Cadastro';
                        $mail->Body = '
                        <div style="font-family: \'Montserrat\', sans-serif; background-color: #f4f4f4; color: #333; padding: 20px; text-align: center;">
                            <img src="' . $logoPath . '" alt="Logo" style="max-width: 200px; margin: 20px 0;">
                            <h1 style="color: #44ff00;">Bem-vindo ao nosso serviço!</h1>
                            <p>Para ativar sua conta, por favor, clique no link abaixo:</p>
                            <p>
                                <a href="http://qxback.com.br/ScreenCadastro/confirmar.php?email=' . urlencode($email) . '&token=' . $token . '" style="text-decoration: none; color: #44ff00; font-weight: bold;">Confirmar E-mail</a>
                            </p>
                            <p>Se você não se cadastrou, ignore este e-mail.</p>
                            <p>Agradecemos a sua escolha!</p>
                        </div>
                        ';
                        $mail->AltBody = 'Por favor, copie e cole o seguinte link em seu navegador: http://qxback.com.br/ScreenCadastro/confirmar.php?email=' . urlencode($email) . '&token=' . $token;

                        $mail->send();
                    } catch (Exception $e) {
                        // Tratamento de erro ao enviar e-mail
                    }
                    header("Location: ../ScreenLogin/index.html?error=É preciso confirmar seu e-mail antes de fazer login. Um novo e-mail de confirmação foi enviado.");
                    exit();
                }

                $_SESSION['usuario'] = $row;

                if ($row['tipo_conta'] == 'inativo') {
                    header("Location: ../ScreenInativado/aviso.php?id=" . urlencode($row['id']) . "&motivo=" . urlencode($row['motivo_inativacao']));
                    exit();
                }
                if ($row['tipo_conta'] == 'financeiro') {
                    header("Location: ../ScreenFinanceiro/index.php");
                } elseif ($row['tipo_conta'] == 'admin') {
                    header("Location: ../ScreenAdmin/index.php");
                } else {
                    header("Location: ../ScreenUser/index.php");
                }
                exit();
            } else {
                header("Location: ../ScreenLogin/index.html?error=Senha incorreta.");
                exit();
            }
        } else {
            header("Location: ../ScreenLogin/index.html?error=Email não cadastrado.");
            exit();
        }
    } else {
        echo "Erro na consulta: " . $conexao->error;
        exit();
    }

    $conexao->close();
}
?>
