<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['usuario']['email']; 
    $subject = "Redefinição de Senha";
    $message = "Clique no link para redefinir sua senha: [link para redefinição]";
    $headers = "From: rodrigo.penhalves@grupoqmax.com.br\r\n" . 
               "Reply-To: rodrigopenhalves7@gmail.com\r\n" . 
               "X-Mailer: PHP/" . phpversion();

    if (mail($email, $subject, $message, $headers)) {
        $_SESSION['message'] = "E-mail enviado com sucesso!";
    } else {
        $_SESSION['message'] = "Falha ao enviar o e-mail.";
    }

    header("Location: index.php"); 
    exit();
}
?>
