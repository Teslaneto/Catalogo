<?php

namespace App\Helpers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Models\Database;

class GenerateLogMovies 
{
    /**
     * Método estático para gerar logs/MoviesLog no arquivo de log.
     *
     * @param string $level   O nível do log (info, warning, error, etc.).
     * @param string $message A mensagem do log.
     * @param ?array $content Dados adicionais para o log (opcional).
     * 
     * @return void
     */
    public static function generateLogMovies(string $level, string $message, ?array $content = null): void
    {
        // Criar o logger
        $log = new Logger('name');

        // Obter a data atual no formato "ddmmyyyy"
        $nameFileLog = date('dmY') . ".log";

        // Criar o caminho dos logs
        $filePath = 'logs/MoviesLog/' . $nameFileLog;

        // Usar StreamHandler para salvar os logs no arquivo
        $log->pushHandler(new StreamHandler($filePath, Logger::DEBUG)); // Definindo o nível de debug

        // Mapear o nível de log para uma constante do Monolog
        switch (strtolower($level)) {
            case 'debug':
                $mappedLevel = Logger::DEBUG;
                break;
            case 'info':
                $mappedLevel = Logger::INFO;
                break;
            case 'notice':
                $mappedLevel = Logger::NOTICE;
                break;
            case 'warning':
                $mappedLevel = Logger::WARNING;
                break;
            case 'error':
                $mappedLevel = Logger::ERROR;
                break;
            case 'critical':
                $mappedLevel = Logger::CRITICAL;
                break;
            case 'alert':
                $mappedLevel = Logger::ALERT;
                break;
            case 'emergency':
                $mappedLevel = Logger::EMERGENCY;
                break;
            default:
                $mappedLevel = Logger::INFO; // Nível padrão caso o nível seja inválido
                break;
        }

        // Salvar o log no arquivo
        $log->log($mappedLevel, $message, $content ?? []);

     // Salvar no banco de dados
     self::logMoviesToDatabase($level, $message, $content);
    }

    /**
     * Salva o log no banco de dados.
     *
     * @param string $level O nível do log.
     * @param string $message A mensagem do log.
     * @param ?array $content Dados adicionais para o log (opcional).
     * 
     * @return void
     */
    private static function logMoviesToDatabase(string $level, string $message, ?array $content = null): void
    {
        // Obter a conexão com o banco
        $db = Database::getConnection(); // Vamos supor que a classe Database tenha um método getConnection

        // Montar a query para inserir os logs
        $sql = 'INSERT INTO logs_movies (endpoint, dados_requisicao, status_resposta, mensagem_erro) 
                VALUES (:endpoint, :dados_requisicao, :status_resposta, :mensagem_erro)';

        // Definir os dados que serão inseridos
        $params = [
            ':endpoint' => $_SERVER['REQUEST_URI'], // Endpoint atual
            ':dados_requisicao' => json_encode($content ?? []), // Dados de requisição codificados em JSON
            ':status_resposta' => 200, // O status de resposta, você pode mudar conforme necessário
            ':mensagem_erro' => $message, // Mensagem do erro
        ];

        // Preparar a query e executá-la
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    }

    
}
