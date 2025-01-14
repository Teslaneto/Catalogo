<?php


class ApiLogic
{

    private $endpoint;
    private $params;

    // --------------------------------------------------
    
    public function __construct($endpoint, $params = null)
    {
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    // --------------------------------------------------
     public static function getApiExternal()
     {
        $url = "https://swapi.py4e.com/api/films/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
        $response = curl_exec($ch);

        if ($response === false) {
            die("Erro na requisição com a API: " . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Erro ao decodificar o JSON: " . json_last_error_msg());
        }

        if (!isset($data['results'])) {
            die("A resposta da API não contém a chave 'results'.");
        }
        
        return $data['results']; 
     }

     public static function getApiAge()
     {
        
        $filmsDate =  self::getApiExternal();
    
        $filmsWithAge = [];
    
        foreach ($filmsDate as $film) {
            if (isset($film['release_date']) && !empty($film['release_date'])) {

                $release_date = $film['release_date'];
                
                $releaseDate = new DateTime($release_date);
                
                $currentDate = new DateTime();
                
                $age = $currentDate->diff($releaseDate);
                
                $film['age'] = [
                    'years' => $age->y,
                    'months' => $age->m,
                    'days' => $age->d,
                ];
            } else {
                $film['age'] = 'Data de lançamento não disponível';
            }
            
            $filmsWithAge[] = $film;
        }
        return $filmsWithAge;
     }
     

     public function get_films_with_age()
    {
        // Obter filmes com a idade
        $filmsWithAge = self::getApiAge();

        return [
            'status' => 'SUCCESS',
            'message' => 'Filmes recuperados com sucesso!',
            'results' => $filmsWithAge
        ];
    }

    public function get_films_title()
    {
        $films = self::getApiAge();
         // Extrair apenas os títulos

        $titles = array_map(function ($film) {
            return $film['title'];
        }, $films);

        return [
            'status' => 'SUCCESS',
            'message' => 'Titulos recuperados com sucesso!',
            'titles' => $titles
        ];
    }
    public function get_planets_films()
    {
        $filmsplanets = self::getApiAge();

        $planets = array_map(function ($plant){
            return $plant['planets'];
        }, $filmsplanets);

        return [
            'status' => 'SUCCESS',
            'message' => 'Todos os planetas foram recuperados com sucesso!',
            'planets' => $planets 
        ];
    }


    public function get_title_films()
    {
        $films = self::getApiAge();

        if(!isset($this->params['title']) || empty($this->params['title']) ){
            return[
                'status' =>'ERROR',
                'message' => 'O parametro "title" é obrigatorio',
                'results' => []
            ];
        }

        $filmTitle = strtolower($this->params['title']);

        
        // Buscar filme pelo nome (case insensitive)
        $filterFilms = array_filter($films, function ($film) use ($filmTitle) {
            return strpos(strtolower($film['title']), $filmTitle) !== false;
        });

        if (empty($filterFilms)) {
            return [
                'status' => 'ERROR',
                'message' => 'Nenhum filme encontrado com o nome fornecido.',
                'results' => []
            ];
        }
    
        return [
            'status' => 'SUCCESS',
            'message' => 'Filme encontrado com sucesso!',
            'results' => array_values($filterFilms) // Reindexar o array
        ];
    }
   
    public function get_idepisode_films()
    {
        $films = self::getApiAge();

        if(!isset($this->params['id']) || empty($this->params['id']) ){
            return[
                'status' =>'ERROR',
                'message' => 'O parametro "id" é obrigatorio',
                'results' => []
            ];
        }

        $filmIdepisode = strtolower($this->params['id']);

        
        // Buscar filme pelo nome (case insensitive)
        $filterFilms = array_filter($films, function ($film) use ($filmIdepisode) {
            return strpos(strtolower($film['episode_id']),$filmIdepisode) !== false;
        });

        if (empty($filterFilms)) {
            return [
                'status' => 'ERROR',
                'message' => 'Nenhum filme encontrado com o id fornecido.',
                'results' => []
            ];
        }
    
        return [
            'status' => 'SUCCESS',
            'message' => 'Filme encontrado com sucesso!',
            'results' => array_values($filterFilms) // Reindexar o array
        ];
    }
    // --------------------------------------------------

    public function endpoint_exists()
    {
        return method_exists($this, $this->endpoint);
    }

    public function error_response($message)
    {
        return [
            'status' => 'ERROR',
            'message' => $message,
            'results' => []
        ];
    }

    public function status()
    {
        return [
            'status' => 'SUCCESS',
            'message' => 'API is running ok!',
            'results' => null
        ];
    }


}

