<?php
require_once __DIR__ . '/../middleware/verifica_login.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../models/Noticia.php';

$noticiaModel = new Noticia($pdo);
$minhasNoticias = $noticiaModel->listarPorAutor($_SESSION['usuario_id']);

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <?php if ($message = getFlashMessage()): ?>
        <div class="alert"><?= esc($message) ?></div>
    <?php endif; ?>
    <h1>Dashboard</h1>
    <p>Olá, <?= esc($_SESSION['usuario_nome']) ?>. Aqui estão suas notícias cadastradas.</p>
    <p>Você tem <?= count($minhasNoticias) ?> notícia(s) publicadas.</p>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h2>Suas notícias</h2>
            <?php if (empty($minhasNoticias)): ?>
                <p>Você ainda não publicou nenhuma notícia.</p>
                <a class="link-button" href="nova_noticia.php">Publicar primeira notícia</a>
            <?php else: ?>
                <ul class="mini-list">
                    <?php foreach ($minhasNoticias as $noticia): ?>
                        <li>
                            <strong><?= esc($noticia['titulo']) ?></strong>
                            <div class="mini-actions">
                                <a href="editar_noticia.php?id=<?= esc($noticia['id']) ?>">Editar</a>
                                <a href="excluir_noticia.php?id=<?= esc($noticia['id']) ?>" class="danger">Excluir</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="dashboard-card">
            <h2>Opções de conta</h2>
            <ul class="mini-list">
                <li><a href="nova_noticia.php">Nova notícia</a></li>
                <?php if (!empty($_SESSION['usuario_role']) && $_SESSION['usuario_role'] === 'admin'): ?>
                    <li><a href="admin_users.php">Gerenciar usuários</a></li>
                <?php endif; ?>
                <li><a href="../user/editar_usuario.php">Editar perfil</a></li>
                <li><a href="../user/excluir_usuario.php" class="danger">Excluir conta</a></li>
                <li><a href="../public/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../views/footer.php';
