<?php
require_once __DIR__ . '/../middleware/verifica_login.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../controllers/noticiaController.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$controller = new NoticiaController($pdo);
$noticia = $id ? $controller->buscar($id) : null;

if (!$noticia || $noticia['autor'] !== $_SESSION['usuario_id']) {
    flash('Notícia não encontrada ou acesso negado.');
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $conteudo = trim($_POST['noticia'] ?? '');
    $imagem = trim($_POST['imagem'] ?? '');

    if ($titulo === '' || $conteudo === '') {
        flash('Título e conteúdo são obrigatórios.');
        redirect('editar_noticia.php?id=' . $noticia['id']);
    }

    if ($controller->atualizar($noticia['id'], $titulo, $conteudo, $imagem ?: null)) {
        flash('Notícia atualizada com sucesso.');
        redirect('dashboard.php');
    }

    flash('Erro ao atualizar a notícia.');
    redirect('editar_noticia.php?id=' . $noticia['id']);
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <h1>Editar notícia</h1>
    <?php if ($message = getFlashMessage()): ?>
        <div class="alert"><?= esc($message) ?></div>
    <?php endif; ?>
    <form method="post" class="form-card">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" value="<?= esc($noticia['titulo']) ?>" required>

        <label for="noticia">Notícia</label>
        <textarea id="noticia" name="noticia" rows="6" required><?= esc($noticia['noticia']) ?></textarea>

        <label for="imagem">URL da imagem (opcional)</label>
        <input type="url" id="imagem" name="imagem" value="<?= esc($noticia['imagem']) ?>">

        <button type="submit" class="btn">Salvar alterações</button>
    </form>
</main>
<?php require_once __DIR__ . '/../views/footer.php';
