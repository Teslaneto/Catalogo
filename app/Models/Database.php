<?php
namespace App\Models;

use App\Helpers\GenerateLog;
use \PDO;
use \PDOException;

class Database 
{
    private $host;
    private $dbname;
    private $user;
    private $pass;
    private static $pdo;  // A propriedade $pdo precisa ser estática

    // Construtor atualizado, não será mais necessário instanciar para obter conexão
    public function __construct($host, $dbname, $user, $pass) 
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->pass = $pass;

        // Se não houver conexão ativa, tenta conectar
        if (self::$pdo === null) {
            $this->connect();
        }
    }

    /**
     * Método para fazer a conexão com o banco de dados.
     */
    private function connect() 
    {
        try {
            // Conectar ao banco de dados
            self::$pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Log de sucesso
            GenerateLog::generateLog("INFO", "BANCO DE DADOS ESTÁ DISPONÍVEL | DataBase", []);
        } catch (PDOException $e) {
            // Log de erro
            GenerateLog::generateLog("ERROR", "ERRO AO CONECTAR AO BANCO DE DADOS: " . $e->getMessage(), []);
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Método estático para obter a conexão PDO.
     * 
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        // Se não houver conexão ativa, cria uma nova
        if (self::$pdo === null) {
            $dbConfig = require './config/config.php';  // Carregar as configurações do banco
            new self($dbConfig['db']['host'], $dbConfig['db']['name'], $dbConfig['db']['user'], $dbConfig['db']['pass']);
        }

        return self::$pdo;
    }

    /**
     * Método para executar uma query no banco de dados.
     *
     * @param string $sql A query SQL a ser executada.
     * @param array $params Parâmetros da query (opcional).
     * 
     * @return PDOStatement
     */
    public function query($sql, $params = []) 
    {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
