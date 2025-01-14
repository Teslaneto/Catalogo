<?php

// Carregar o Composer
require_once './vendor/autoload.php';
require_once './config/config.php';

// Namespaces
use App\Controllers\FilmController;
use App\Controllers\MoviesDetailsController;

// Função para renderizar views
function renderView($view, $data = []) {
    $file = "./app/Views/{$view}.php";
    if (file_exists($file)) {
        extract($data);
        include_once $file;
    } else {
        http_response_code(404);
        echo "Página não encontrada.";
    }
}

// Roteamento básico
try {
    $episodeId = isset($_GET['episode_id']) ? filter_var($_GET['episode_id'], FILTER_SANITIZE_NUMBER_INT) : null;
    $page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_SANITIZE_STRING) : 'index';

    switch ($page) {
        case 'MoviesDetails':
            $moviesDetails = new MoviesDetailsController();
            $moviesDetails->getfilmsid($episodeId);
            break;

        case 'index':
        default:
            $filmsView = new FilmController();
            $filmsView->view();
            break;
    }
} catch (Exception $e) {
    // Captura erros e exibe uma mensagem amigável
    http_response_code(500);
    echo "Erro interno do servidor: " . htmlspecialchars($e->getMessage());
    // Opcional: Logar o erro
}
