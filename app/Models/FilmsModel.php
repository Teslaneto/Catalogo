<?php
namespace App\Models;


use App\Helpers\GenerateLog;

class filmsModel
{

    public static function getfilms()
    {
        // URL da API
        $url = "http://localhost/Catalogo/api/index.php?endpoint=get_films_with_age";

        // Inicializa o cURL
        $ch = curl_init($url);

        // Configurações do cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout de 10 segundos

        // Executa a requisição
        $response = curl_exec($ch);

        // Verifica se houve erro no cURL
        if ($response === false) {
            GenerateLog::generateLog("ERROR", "A API NÃO ESTÁ FUNCIONADO O cURL. | FilmsModel", []);
            die("Erro na requisição com a API: " . curl_error($ch));
        }

        // Fecha a conexão cURL
        curl_close($ch);

        // Decodifica o JSON
        $data = json_decode($response, true);

        // Verifica se o JSON foi decodificado corretamente
        if (json_last_error() !== JSON_ERROR_NONE) {
            GenerateLog::generateLog("ERROR", "O JSON NA API ESTAR COM ERRO DE DECODIFICAÇÃO | FilmsModel", []);
            die("Erro ao decodificar o JSON: " . json_last_error_msg());
        }

        // Verifica se a chave 'results' existe
        if (!isset($data['data'])) {
            GenerateLog::generateLog("ERROR", "A API NÃO ESTÁ TRAZENDO A CHAVE 'RESULTS'. | FilmsModel ", []);
            die("A resposta da API não contém a chave 'data'.");
        }
        
        GenerateLog::generateLog("INFO", "A API ESTÁ TRAZENDO OS FILMES. | FilmsModel", []);
        return $data['data']; // Retorna a lista de filmes

    }


}