<?php
require_once __DIR__ . '/../middleware/verifica_login.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../controllers/authController.php';

$usuarioId = $_SESSION['usuario_id'];
$usuarioModel = new Usuario($pdo);
$usuario = $usuarioModel->buscarPorId($usuarioId);

if (!$usuario) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($nome === '' || $email === '') {
        flash('Nome e email são obrigatórios.');
        redirect('editar_usuario.php');
    }

    $auth = new AuthController($pdo);

    if ($auth->atualizar($usuarioId, $nome, $email, $senha ?: null)) {
        $_SESSION['usuario_nome'] = $nome;
        flash('Conta atualizada com sucesso.');
        redirect('../private/dashboard.php');
    }

    flash('Erro ao atualizar a conta. Verifique se o email já existe.');
    redirect('editar_usuario.php');
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <h1>Editar usuário</h1>
    <?php if ($message = getFlashMessage()): ?>
        <div class="alert"><?= esc($message) ?></div>
    <?php endif; ?>
    <form method="post" class="form-card">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="<?= esc($usuario['nome']) ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= esc($usuario['email']) ?>" required>

        <label for="senha">Nova senha (deixe em branco para manter)</label>
        <input type="password" id="senha" name="senha">

        <button type="submit" class="btn">Salvar</button>
    </form>
</main>
<?php require_once __DIR__ . '/../views/footer.php';
