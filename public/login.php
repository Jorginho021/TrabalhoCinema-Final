<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../controllers/authController.php';

if (isLogged()) {
    redirect('../private/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $auth = new AuthController($pdo);

    if ($auth->login($email, $senha)) {
        flash('Login efetuado com sucesso.');
        redirect('../private/dashboard.php');
    }

    flash('Email ou senha inválidos.');
    redirect('login.php');
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <h1>Login</h1>
    <?php if ($message = getFlashMessage()): ?>
        <div class="alert"><?= esc($message) ?></div>
    <?php endif; ?>
    <form method="post" class="form-card">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit" class="btn">Entrar</button>
    </form>
    <p class="small-text">Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
</main>
<?php require_once __DIR__ . '/../views/footer.php';
