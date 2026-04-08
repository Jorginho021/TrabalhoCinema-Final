<?php
require_once __DIR__ . '/../middleware/verifica_login.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../controllers/noticiaController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $noticia = trim($_POST['noticia'] ?? '');
    $imagem = trim($_POST['imagem'] ?? '');

    if ($titulo === '' || $noticia === '') {
        flash('Título e conteúdo são obrigatórios.');
        redirect('nova_noticia.php');
    }

    $controller = new NoticiaController($pdo);
    if ($controller->criar($titulo, $noticia, $_SESSION['usuario_id'], $imagem ?: null)) {
        flash('Notícia criada com sucesso.');
        redirect('dashboard.php');
    }

    flash('Erro ao criar notícia.');
    redirect('nova_noticia.php');
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <h1>Nova notícia</h1>
    <?php if ($message = getFlashMessage()): ?>
        <div class="alert"><?= esc($message) ?></div>
    <?php endif; ?>
    <form method="post" class="form-card">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="noticia">Notícia</label>
        <textarea id="noticia" name="noticia" rows="6" required></textarea>

        <label for="imagem">URL da imagem (opcional)</label>
        <input type="url" id="imagem" name="imagem" placeholder="https://example.com/imagem.jpg">

        <button type="submit" class="btn">Publicar</button>
    </form>
</main>
<?php require_once __DIR__ . '/../views/footer.php';
