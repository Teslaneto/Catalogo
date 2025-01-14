<?php
//CARREGAR O BANCO DE DADOS
use App\Models\Database;

// Configurações gerais
define('BASE_URL', 'http://localhost/Catalogo/');
define('DB_HOST', 'db');
define('DB_NAME', 'starwars_api');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// Conexão com banco de dados
$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);