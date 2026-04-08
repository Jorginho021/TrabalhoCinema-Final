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
    if ($controller->excluir($noticia['id'])) {
        flash('Notícia excluída com sucesso.');
        redirect('dashboard.php');
    }

    flash('Erro ao excluir a notícia.');
    redirect('excluir_noticia.php?id=' . $noticia['id']);
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <h1>Excluir notícia</h1>
    <p>Deseja mesmo excluir a notícia <strong><?= esc($noticia['titulo']) ?></strong>?</p>
    <form method="post" class="form-card">
        <button type="submit" class="btn danger">Confirmar exclusão</button>
        <a href="dashboard.php" class="link-button">Cancelar</a>
    </form>
</main>
<?php require_once __DIR__ . '/../views/footer.php';
