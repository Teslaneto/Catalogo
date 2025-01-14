# Lentídão devido a várias requisições de rede para a API.
- Havia lentidão ao buscar informações sobre os personagens de cada filme. Cada filme estava fazendo uma requisição cURL, o que poderia causar atraso no carregamento.
- CODIGO ANTIGO ESTAVA EM **`app/Controllers/MoviesDetailsControllers`**
````
private function getCharacterNames($characterUrls)
    {
        $characterNames = [];

        // Para cada URL de personagem, faz uma nova requisição para obter o nome
        foreach ($characterUrls as $url) {
            $characterData = $this->getApiData($url);
            if ($characterData && isset($characterData['name'])) {
                $characterNames[] = $characterData['name'];  // Adiciona o nome do personagem
            }
        }

        return $characterNames;
    }

    private function getApiData($url)
    {
        // Faz uma requisição cURL para pegar os dados de um personagem
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);

        // Verifica se houve erro na requisição
        if ($response === false) {
            return null;
        }

        // Decodifica o JSON
        $data = json_decode($response, true);
        curl_close($ch);

        return $data;
    }
````   

- CODIGO NOVO:

````
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
 ````   
- Esse código faz uso de ``multi-cURL,`` que é uma técnica para realizar várias requisições ``HTTP`` simultaneamente, ao invés de fazer uma requisição por vez. Essa abordagem é útil para melhorar a performance quando se tem múltiplas requisições independentes, como é o caso das requisições para buscar os nomes dos personagens a partir de `` URLs`` fornecidas.

### EXPLICAÇÃO:
````
$multiCurl = curl_multi_init();
$curlHandles = [];
$responses = [];
````

- ``curl_multi_init()``: Inicializa o manipulador multi-cURL. Isso permite que execute várias requisições cURL ao mesmo tempo.
- ``$curlHandles``: Um array onde os handles cURL individuais serão armazenados. Cada ``handle (Identificador único que representa uma sessão de requisição HTTP)`` é uma requisição cURL.
- ``$responses``: Um array onde as respostas das requisições cURL serão armazenadas.

````
foreach ($characterUrls as $url) {
    $ch = curl_init($url); // Inicializa um handle cURL para a URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Define que a resposta será retornada como string
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Define o tempo máximo de espera para a requisição
    curl_multi_add_handle($multiCurl, $ch); // Adiciona o handle cURL ao multi-cURL
    $curlHandles[] = $ch; // Armazena o handle cURL no array
}
````
- ``curl_init($url)``: Cria uma requisição cURL para a URL fornecida.
- ``curl_setopt($ch, CURLOPT_RETURNTRANSFER, true)``: Faz com que a resposta da requisição cURL seja retornada como uma string, ao invés de ser exibida diretamente.
- ``curl_setopt($ch, CURLOPT_TIMEOUT, 10)``: Define um tempo limite de 10 segundos para cada requisição. Caso a requisição não seja concluída dentro desse período, ela será abortada.
- ``curl_multi_add_handle($multiCurl, $ch)``: Adiciona o handle cURL ao multi-cURL para que ele seja executado juntamente com os outros.
- ``$curlHandles[]`` = $ch: Adiciona o handle à lista para poder acessar as respostas mais tarde.

````
$running = null;
do {
    curl_multi_exec($multiCurl, $running); // Executa as requisições
} while ($running > 0); // Continua executando até todas as requisições terminarem
````
- ``curl_multi_exec($multiCurl, $running)``: Executa as requisições cURL de forma paralela. O parâmetro $running contém o número de requisições ainda em andamento.
- O loop do ... while continua executando até que todas as requisições tenham sido completadas.

````
foreach ($curlHandles as $ch) {
    $response = curl_multi_getcontent($ch); // Obtém a resposta de cada requisição cURL
    $responses[] = json_decode($response, true); // Decodifica a resposta JSON e adiciona ao array de respostas
    curl_multi_remove_handle($multiCurl, $ch); // Remove o handle do multi-cURL
}
````
- ``curl_multi_getcontent($ch)``: Obtém o conteúdo da resposta da requisição cURL. Esse conteúdo será a resposta da API (geralmente em formato JSON).
- ``json_decode($response, true)``: Decodifica a resposta JSON em um array associativo PHP.
- ``curl_multi_remove_handle($multiCurl, $ch)``: Remove o handle cURL do multi-cURL após a requisição ter sido concluída.

````
curl_multi_close($multiCurl); 
````
- ``curl_multi_close($multiCurl)``: Libera os recursos associados ao manipulador multi-cURL.

````
$characterNames = [];
foreach ($responses as $characterData) {
    if ($characterData && isset($characterData['name'])) {
        $characterNames[] = $characterData['name']; // Adiciona o nome do personagem ao array
    }
}
`````
- ``$characterNames[] = $characterData['name']``: Para cada resposta (que contém os dados do personagem), o código verifica se o campo ``'name'`` está presente. Se estiver, o nome do personagem é adicionado ao array ``$characterNames``.

``
return $characterNames;
``
- O array com os nomes dos personagens é retornado.

### Melhoria Realizada
- A principal melhoria no código foi a introdução do multi-cURL, que permite realizar várias requisições HTTP em paralelo. Antes, cada URL de personagem era requisitada uma por vez, o que poderia ser muito lento, especialmente se houver muitos personagens. Com o multi-cURL, todas as requisições são feitas ao mesmo tempo, reduzindo significativamente o tempo total de execução.

### Vantagens:

- 1 * Redução de Tempo: Ao invés de esperar cada requisição cURL ser concluída antes de começar a próxima, as requisições são feitas simultaneamente, o que acelera a coleta de dados.
- 2 * Eficiência: Isso economiza o tempo de espera de forma considerável quando se trabalha com múltiplas requisições independentes.
- 3 * Escalabilidade: Essa técnica pode lidar com um número maior de requisições simultâneas, melhorando a performance geral, especialmente em sistemas com muitos dados a serem buscados de APIs externas.