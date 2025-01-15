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
    <link rel="stylesheet" type="text/css" href="./assets/css/movies.css">
</head>
<body class="background">
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
                        <a class="nav-link text-white" href="<?=BASE_URL?>">Lista de Filmes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="https://www.google.com/">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <br>
        <a href="<?=BASE_URL?>" class="btn btn-danger">Voltar</a>
        <div class="content">
            <h1><span class="red">Movie</span>Flix</h1>
            <br>
            <h1 class="title"><?= htmlspecialchars($Film['title'] ?? 'Sem título'); ?></h1>
            <p class="standard"><?= htmlspecialchars($Film['release_date'] ?? 'Data desconhecida'); ?></p>
            <p><span class="standard">Nº episódio: </span><?= htmlspecialchars($Film['episode_id'] ?? 'Sem Numero de Ep'); ?></p>
            <p><?= htmlspecialchars($Film['opening_crawl'] ?? 'Sem título'); ?></p>
            <p>
                <span class="standard">Idade do Filme: </span>
                <?= htmlspecialchars($Film['age']['years']) ?> anos, 
                <?= htmlspecialchars($Film['age']['months']) ?> meses, 
                <?= htmlspecialchars($Film['age']['days']) ?> dias
            </p>
            <p><span class="standard">Diretor(a): </span><?= htmlspecialchars($Film['director'] ?? 'Sem título'); ?></p>
            <p><span class="standard">Produtor(es): </span><?= htmlspecialchars($Film['producer'] ?? 'Sem título'); ?></p>
            <p><span class="standard">Personagens: </span>
            <?php if (!empty($Film['character_names'])): ?>
                <?php foreach ($Film['character_names'] as $name): ?>
                    <li><?= htmlspecialchars($name); ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum personagem encontrado.</p>
            <?php endif; ?></p>
        </div>
    </div>
    <!-- Rodapé -->
    <br>
    <div class="footer">
        <p>&copy; 2025 MovieFlix. Todos os direitos reservados.</p>
    </div>
</body>
</html>