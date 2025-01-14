<?php
namespace App\Controllers;

use App\Models\filmsModel;
use App\Helpers\GenerateLog;


class FilmController {


    // Método para exibir filmes com idade na View
    public function view() {
        try {
            // Chama o método filmAge() do model para obter os filmes com a idade calculada
            $filmsWithAge = FilmsModel::getfilms();

            // Verifica se há filmes e passa para a view
            if (!empty($filmsWithAge)) {
                $this->render('Films', ['dateFilms' => $filmsWithAge]);
            } else {
                //$this->render('404');
            }
        } catch (\Exception $e) {
            // Se houver algum erro, loga o erro
            GenerateLog::generateLog("ERROR","PROBLEMA NA VIEW | FilmController ".$e->getMessage(),[]);
            $this->render('Error', ['message' => $e->getMessage()]);
        }
    }

    // Função para renderizar as views
    private function render($view, $data = []) {
        // Torna os dados acessíveis na View
        extract($data);
        include_once("./app/Views/{$view}.php");
    }

   

}