<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../models/Noticia.php';
require_once __DIR__ . '/../models/Avaliacao.php';
require_once __DIR__ . '/../controllers/avaliacaoController.php';

$noticiaModel = new Noticia($pdo);
$avaliacaoController = new AvaliacaoController($pdo);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$noticia = $id ? $noticiaModel->buscarPorId($id) : null;
$topAvaliadas = $noticiaModel->listarTopAvaliadas(5);
$currentAvaliacao = null;
$avaliacoesDetalhes = [];

if ($noticia) {
    $avaliacaoModel = new Avaliacao($pdo);
    if (isLogged()) {
        $currentAvaliacao = $avaliacaoModel->buscarPorUsuarioENoticia($_SESSION['usuario_id'], $noticia['id']);
    }
    $avaliacoesDetalhes = $avaliacaoModel->listarPorNoticia($noticia['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLogged() && $noticia) {
    $estrelas = filter_input(INPUT_POST, 'estrelas', FILTER_VALIDATE_INT);

    if ($estrelas >= 1 && $estrelas <= 5) {
        $avaliacaoController->avaliar($noticia['id'], $_SESSION['usuario_id'], $estrelas);
        flash('Avaliação registrada.');
    } else {
        flash('Selecione de 1 a 5 estrelas.');
    }

    redirect('noticia.php?id=' . $noticia['id']);
}

$noticias = $noticiaModel->listarTodas();

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <?php if ($message = getFlashMessage()): ?>
        <div class="alert"><?= esc($message) ?></div>
    <?php endif; ?>

    <?php if ($noticia): ?>
        <article class="detail-article">
            <?php if (!empty($noticia['imagem'])): ?>
                <img src="<?= esc($noticia['imagem']) ?>" alt="<?= esc($noticia['titulo']) ?>">
            <?php endif; ?>
            <div class="detail-body">
                <h1><?= esc($noticia['titulo']) ?></h1>
                <div class="meta-row">
                    <span><?= esc($noticia['autor_nome']) ?></span>
                    <span><?= esc(formatDate($noticia['data'])) ?></span>
                </div>
                <p><?= nl2br(esc($noticia['noticia'])) ?></p>
                <div class="rating-summary">
                    <strong>Classificação:</strong>
                    <span><?= number_format($noticia['media_avaliacao'], 1) ?> / 5</span>
                    <span>(<?= esc($noticia['total_avaliacoes']) ?> avaliações)</span>
                </div>
                <div class="rating-stars">
                    <?php
                        $filledStars = floor($noticia['media_avaliacao']);
                        for ($star = 1; $star <= $filledStars; $star++):
                    ?>
                        <span class="star filled">★</span>
                    <?php endfor; ?>
                    <span class="rating-label"><?= number_format($noticia['media_avaliacao'], 1) ?></span>
                </div>

                <?php if (isLogged() && $noticia['autor'] === $_SESSION['usuario_id']): ?>
                    <div class="detail-actions">
                        <a class="btn" href="../private/editar_noticia.php?id=<?= esc($noticia['id']) ?>">Editar notícia</a>
                        <a class="btn danger" href="../private/excluir_noticia.php?id=<?= esc($noticia['id']) ?>">Excluir notícia</a>
                    </div>
                <?php endif; ?>
            </div>
        </article>

        <?php if (isLogged()): ?>
            <section class="rating-form">
                <h2>Deixe sua avaliação</h2>
                <?php if ($currentAvaliacao): ?>
                    <div class="info-box">Sua avaliação atual: <?= esc($currentAvaliacao['estrelas']) ?> ★</div>
                <?php endif; ?>
                <form method="post">
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <label>
                                <input type="radio" name="estrelas" value="<?= $i ?>" <?= $currentAvaliacao && $currentAvaliacao['estrelas'] === $i ? 'checked' : '' ?> />
                                <span class="star"><?= $i ?>★</span>
                            </label>
                        <?php endfor; ?>
                    </div>
                    <button type="submit" class="btn">Avaliar</button>
                </form>
            </section>
        <?php else: ?>
            <div class="info-box">Faça login para avaliar esta notícia.</div>
        <?php endif; ?>

        <section class="review-panel">
            <h2>Avaliações de usuários</h2>
            <?php if (!empty($avaliacoesDetalhes)): ?>
                <ul class="review-list">
                    <?php foreach ($avaliacoesDetalhes as $avaliacao): ?>
                        <li>
                            <strong><?= esc($avaliacao['usuario_nome']) ?></strong>
                            <span class="review-stars"><?= esc($avaliacao['estrelas']) ?>★</span>
                            <span class="review-date"><?= esc(formatDate($avaliacao['criado_em'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhuma avaliação registrada ainda.</p>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <header class="section-header">
            <h1>Notícias</h1>
            <p>Confira as últimas matérias publicadas no portal.</p>
        </header>

        <section class="filter-links">
            <span>Filtrar por estrelas:</span>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=5">5 estrelas</a>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=4">4 estrelas</a>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=3">3 estrelas</a>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=2">2 estrelas</a>
            <a class="link-button filter-button" href="filmes_por_estrelas.php?rating=1">1 estrela</a>
        </section>

        <?php if (!empty($topAvaliadas)): ?>
            <section class="ranking-panel">
                <div class="ranking-card">
                    <div class="ranking-card-header">
                        <h2>Ranking de filmes</h2>
                        <p>Os títulos mais bem avaliados pelos leitores.</p>
                    </div>
                    <ol class="ranking-list">
                        <?php foreach ($topAvaliadas as $index => $top): ?>
                            <li>
                                <span class="rank-badge">#<?= $index + 1 ?></span>
                                <a href="noticia.php?id=<?= esc($top['id']) ?>"><?= esc($top['titulo']) ?></a>
                                <span class="rating-pill"><?= number_format($top['media_avaliacao'], 1) ?> <small>★</small></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </section>
        <?php endif; ?>

        <div class="news-grid">
            <?php foreach ($noticias as $noticiaItem): ?>
                <article class="news-card">
                    <?php if (!empty($noticiaItem['imagem'])): ?>
                        <img src="<?= esc($noticiaItem['imagem']) ?>" alt="<?= esc($noticiaItem['titulo']) ?>">
                    <?php else: ?>
                        <div class="news-card-placeholder">Cinema</div>
                    <?php endif; ?>
                    <div class="news-card-body">
                        <h2><?= esc($noticiaItem['titulo']) ?></h2>
                        <div class="rating-stars small">
                            <?php
                                $filledStars = floor($noticiaItem['media_avaliacao']);
                                for ($star = 1; $star <= $filledStars; $star++):
                            ?>
                                <span class="star filled">★</span>
                            <?php endfor; ?>
                            <span class="rating-label"><?= number_format($noticiaItem['media_avaliacao'], 1) ?></span>
                        </div>
                        <p><?= esc(substr($noticiaItem['noticia'], 0, 140)) ?><?= strlen($noticiaItem['noticia']) > 140 ? '...' : '' ?></p>
                        <div class="meta-row">
                            <span><?= esc($noticiaItem['autor_nome']) ?></span>
                            <span><?= esc(formatDate($noticiaItem['data'])) ?></span>
                        </div>
                        <div class="rating-row">
                            <span class="rating-pill"><?= number_format($noticiaItem['media_avaliacao'], 1) ?> <small>★</small></span>
                            <span>(<?= esc($noticiaItem['total_avaliacoes']) ?> avaliações)</span>
                        </div>
                        <div class="news-card-actions">
                            <a class="link-button" href="noticia.php?id=<?= esc($noticiaItem['id']) ?>">Ler mais</a>
                            <?php if (isLogged() && $noticiaItem['autor'] === $_SESSION['usuario_id']): ?>
                                <a class="link-button" href="../private/editar_noticia.php?id=<?= esc($noticiaItem['id']) ?>">Editar</a>
                                <a class="link-button danger" href="../private/excluir_noticia.php?id=<?= esc($noticiaItem['id']) ?>">Excluir</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
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
