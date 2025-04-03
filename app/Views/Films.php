<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieFlix</title>
    <!-- Incluindo o Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Bootstrap/bootstrap-4.1.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../Bootstrap/bootstrap-4.1.3-dist/js/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="./assets/css/films.css">
</head>
<body class="bg-dark-custom">

    <!-- Cabeçalho -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#"><strong><span class="red">Movie</span>Flix</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?=BASE_URL?>">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#idfilmes">Lista de Filmes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="https://www.google.com/">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Carrossel de destaque -->
    <div id="carouselExampleAutoplaying" class="carousel slide carousel-fade mt-3 pt-3" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./assets/img/movieflix.png" class="d-block w-100" alt="Filme 1">
            </div>  
    </div>
<?php
//var_dump($dateFilms);
?>
    <!-- Seção de Lançamentos -->
    <div id="idfilmes" class="container mt-5">
        <h2 class="category-title">Lista de Filmes:</h2>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($dateFilms['results'] as $film): ?>
                <div class="col">
                    <a href="<?= BASE_URL ?>?page=MoviesDetails&episode_id=<?= $film['episode_id'] ?>" class="card-link">
                        <div class="card movie-item">
                            <img src="https://img.freepik.com/fotos-gratis/superficie-abstrata-e-texturas-de-parede-de-pedra-branca-de-concreta_74190-8189.jpg?t=st=1743679879~exp=1743683479~hmac=7f3404bed1237c3ea4cbae87eae9797490eb7dbb5399e3535f807499b70959e2&w=1380" 
                                class="card-img-top movie-image" 
                                alt="<?= htmlspecialchars($film['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($film['title']); ?></h5>
                                <p class="card-text">Lançado em: <?= htmlspecialchars($film['release_date']); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p>&copy; 2025 MovieFlix. Todos os direitos reservados.</p>
    </div>
    <?php
    

    
    ?>

    <!-- Incluindo o JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
