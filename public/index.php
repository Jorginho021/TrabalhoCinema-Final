<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../models/Noticia.php';

$noticiaModel = new Noticia($pdo);
$noticias = $noticiaModel->listarTodas();

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="home-page">
    <section class="hero">
        <div>
            <h1>Portal de Notícias Cinema</h1>
            <p>Explore as últimas notícias sobre cinema, streaming e lançamentos. Aqui você encontra conteúdo atualizado e avaliações reais.</p>
            <a class="btn" href="noticia.php">Ver todas as notícias</a>
        </div>
        <div class="hero-image"></div>
    </section>

    <section class="news-section">
        <header>
            <h2>Últimas notícias</h2>
            <p>Notícias mais recentes publicadas por nossos autores.</p>
        </header>

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
                            <h3><?= esc($noticia['titulo']) ?></h3>
                            <p><?= esc(substr($noticia['noticia'], 0, 140)) ?><?= strlen($noticia['noticia']) > 140 ? '...' : '' ?></p>
                            <div class="meta-row">
                                <span><?= esc($noticia['autor_nome']) ?></span>
                                <span><?= esc(formatDate($noticia['data'])) ?></span>
                            </div>
                            <div class="rating-stars small">
                                <?php
                                    $filledStars = floor($noticia['media_avaliacao']);
                                    for ($star = 1; $star <= $filledStars; $star++):
                                ?>
                                    <span class="star filled">★</span>
                                <?php endfor; ?>
                                <span class="rating-label"><?= number_format($noticia['media_avaliacao'], 1) ?></span>
                            </div>
                            <div class="rating-row">
                                <span class="rating-pill"><?= number_format($noticia['media_avaliacao'], 1) ?> <small>★</small></span>
                                <span>(<?= esc($noticia['total_avaliacoes']) ?> avaliações)</span>
                            </div>
                            <a class="link-button" href="noticia.php?id=<?= esc($noticia['id']) ?>">Ler mais</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-state">Ainda não há notícias publicadas. Cadastre-se e publique a primeira notícia.</p>
        <?php endif; ?>
    </section>
</main>

<!-- Modal para visualizar imagem em tamanho completo -->
<div id="imageModal" class="modal">
    <span class="close-modal">&times;</span>
    <div class="modal-content">
        <img id="modalImage" src="" alt="Imagem do filme em tamanho completo">
    </div>
</div>

<?php require_once __DIR__ . '/../views/footer.php';
