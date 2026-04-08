<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../models/Noticia.php';

$rating = filter_input(INPUT_GET, 'rating', FILTER_VALIDATE_INT);
if ($rating === false || $rating < 1 || $rating > 5) {
    $rating = 5;
}

$noticiaModel = new Noticia($pdo);
$noticias = $noticiaModel->listarPorEstrelas($rating);
$label = $rating === 1 ? '1 estrela' : "$rating estrelas";

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <header class="section-header">
        <h1>Filmes com <?= esc($label) ?></h1>
        <p>Mostrando notícias com média de avaliação de <?= esc($label) ?>.</p>
    </header>

    <section class="filter-links">
        <span>Ver também:</span>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=5">5 estrelas</a>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=4">4 estrelas</a>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=3">3 estrelas</a>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=2">2 estrelas</a>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=1">1 estrela</a>
    <?php if (!empty($noticias)): ?>
        <div class="news-grid">
            <?php foreach ($noticias as $noticia): ?>
                <article class="news-card">
                    <?php if (!empty($noticia['imagem'])): ?>
                        <img src="<?= esc($noticia['imagem']) ?>" alt="<?= esc($noticia['titulo']) ?>">
                    <?php else: ?>
                        <div class="news-card-placeholder">Sem imagem</div>
                    <?php endif; ?>
                    <div class="news-card-body">
                        <h2><?= esc($noticia['titulo']) ?></h2>
                        <div class="rating-stars small">
                            <?php
                                $filledStars = floor($noticia['media_avaliacao']);
                                for ($star = 1; $star <= $filledStars; $star++):
                            ?>
                                <span class="star filled">★</span>
                            <?php endfor; ?>
                            <span class="rating-label"><?= number_format($noticia['media_avaliacao'], 1) ?></span>
                        </div>
                        <p><?= esc(substr($noticia['noticia'], 0, 140)) ?><?= strlen($noticia['noticia']) > 140 ? '...' : '' ?></p>
                        <div class="meta-row">
                            <span><?= esc($noticia['autor_nome']) ?></span>
                            <span><?= esc(formatDate($noticia['data'])) ?></span>
                        </div>
                        <div class="news-card-actions">
                            <a class="link-button" href="noticia.php?id=<?= esc($noticia['id']) ?>">Ler mais</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert">Nenhum filme encontrado para <?= esc($label) ?>.</div>
    <?php endif; ?>
</main>

<!-- Modal para visualizar imagem em tamanho completo -->
<div id="imageModal" class="modal">
    <span class="close-modal">&times;</span>
    <div class="modal-content">
        <img id="modalImage" src="" alt="Imagem do filme em tamanho completo">
    </div>
</div>

<?php require_once __DIR__ . '/../views/footer.php';
