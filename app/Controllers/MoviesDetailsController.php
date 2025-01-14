<?php
namespace App\Controllers;

use App\Models\filmsModel;
use App\Helpers\GenerateLogMovies;

class MoviesDetailsController 
{
    public function getfilmsid($id)
    {
        // Obter os filmes
        $films = FilmsModel::getfilms();

        // Verificar se o ID não está vazio ou nulo
        if (!empty($id) && $id != null) {
            // Filtra os filmes para encontrar o que corresponde ao episode_id
            $selectedFilm = null;
            foreach ($films['results'] as $film) {
                if ($film['episode_id'] == $id) {
                    $selectedFilm = $film;
                    break;
                }
            }

            // Se o filme for encontrado, passa para a view
            if ($selectedFilm) {
                $characterNames = $this->getCharacterNames($selectedFilm['characters']);
                $selectedFilm['character_names'] = $characterNames;  // Adiciona os nomes dos personagens no filme
                $this->render('MoviesDetails', ['Film' => $selectedFilm]);
                
                GenerateLogMovies::generateLogMovies("INFO","FILME ENCOTRADO NA API | MoviesDetails", []);
            } else {
                // Caso o filme não seja encontrado, exibe uma mensagem ou redireciona
                GenerateLogMovies::generateLogMovies("ERROR","FILME NÃO ENCOTRADO NA API | MoviesDetails", []);
                echo "Filme não encontrado.";
            }
        } else {
            // Caso o ID seja inválido ou não exista
            GenerateLogMovies::generateLogMovies("ERROR","ID DO EPISÓDIO NÃO ENCOTRADO | MoviesDetails", []);
            echo "ID de filme inválido.";
        }
    }

    private function getCharacterNames($characterUrls)
    {
        $multiCurl = curl_multi_init();
        $curlHandles = [];
        $responses = [];

        // Cria um handle cURL para cada URL
        foreach ($characterUrls as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_multi_add_handle($multiCurl, $ch);
            $curlHandles[] = $ch;
        }

        // Executa todas as requisições de uma vez
        $running = null;
        do {
            curl_multi_exec($multiCurl, $running);
        } while ($running > 0);

        // Coleta as respostas
        foreach ($curlHandles as $ch) {
            $response = curl_multi_getcontent($ch);
            $responses[] = json_decode($response, true);
            curl_multi_remove_handle($multiCurl, $ch);
        }

        curl_multi_close($multiCurl);

        // Processa os dados
        $characterNames = [];
        foreach ($responses as $characterData) {
            if ($characterData && isset($characterData['name'])) {
                $characterNames[] = $characterData['name'];
            }
        }

        return $characterNames;
    }
    

    private function render($view, $data = []) 
    {
        // Torna os dados acessíveis na View
        extract($data);
        include_once("./app/Views/{$view}.php");
    }
}