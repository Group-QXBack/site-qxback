<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos e Condições</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #001100;
            overflow: hidden;
        }

        .termos-container {
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            color: #000;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            margin: 1rem;
        }

        .termos-container h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
            color: #001100;
        }

        .termos-container p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .termos-container ol {
            margin-left: 20px;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .termos-container a {
            display: block;
            margin-top: 1rem;
            text-align: center;
            font-size: 1rem;
            color: #001100;
            text-decoration: none;
            font-weight: 600;
        }

        .termos-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="termos-container">
        <h1>Termos e Condições</h1>
        <p>Bem-vindo ao QXBACK. Estes são os nossos termos e condições:</p>
        <ol>
            <li>Você deve fornecer informações verdadeiras e precisas ao se cadastrar.</li>
            <li>É responsável por manter a confidencialidade da sua senha e conta.</li>
            <li>Não deve usar o site para fins ilegais ou não autorizados.</li>
        </ol>
        <p>Se tiver alguma dúvida sobre estes termos, entre em contato conosco.</p>
        <a href="index.php">Voltar para o cadastro</a>
    </div>
</body>
</html>
