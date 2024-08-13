<?php
header('Content-Type: application/json');

include '../ScreenCadastro/config.php';

if (isset($_GET['cnpj'])) {
    $cnpj = $conexao->real_escape_string(trim($_GET['cnpj']));

    if (strlen($cnpj) !== 14) {
        echo json_encode(['error' => 'CNPJ inválido']);
        exit();
    }

    // Primeiro, tentar buscar no banco de dados
    $sql = "SELECT nome_empresa, telefone_empresa, email_empresa FROM indicacoes WHERE cnpj = '$cnpj'";
    $result = $conexao->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $empresa = $result->fetch_assoc();
            echo json_encode($empresa);
        } else {
            // Se a empresa não estiver no banco de dados, buscar na API externa
            $apiUrl = "https://www.receitaws.com.br/v1/cnpj/$cnpj";

            // Verificar se a URL é acessível e capturar o conteúdo
            $apiResponse = @file_get_contents($apiUrl);

            if ($apiResponse === FALSE) {
                echo json_encode(['error' => 'Não foi possível acessar a API externa']);
            } else {
                // Tentar decodificar a resposta JSON da API externa
                $apiData = json_decode($apiResponse, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    if (isset($apiData['status']) && $apiData['status'] == 'ERROR') {
                        echo json_encode(['error' => $apiData['message']]);
                    } else {
                        $dados_empresa = [
                            'nome_empresa' => $apiData['nome'] ?? '',
                            'telefone_empresa' => $apiData['telefone'] ?? '',
                            'email_empresa' => $apiData['email'] ?? '' // Pode não estar disponível na API
                        ];
                        echo json_encode($dados_empresa);
                    }
                } else {
                    echo json_encode(['error' => 'Resposta da API externa não é JSON válido']);
                }
            }
        }
    } else {
        echo json_encode(['error' => 'Erro na consulta ao banco de dados']);
    }
} else {
    echo json_encode(['error' => 'Parâmetro CNPJ não fornecido']);
}

$conexao->close();
?>
