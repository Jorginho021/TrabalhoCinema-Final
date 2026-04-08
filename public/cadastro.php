<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../controllers/authController.php';

if (isLogged()) {
    redirect('../private/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        flash('Preencha todos os campos.');
        redirect('cadastro.php');
    }

    $auth = new AuthController($pdo);

    if ($auth->cadastrar($nome, $email, $senha)) {
        if ($auth->login($email, $senha)) {
            flash('Cadastro realizado com sucesso. Seja bem-vindo!');
            redirect('../private/dashboard.php');
        }
    }

    flash('Usuário já existe ou dados inválidos.');
    redirect('cadastro.php');
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <h1>Cadastro</h1>
    <?php if ($message = getFlashMessage()): ?>
        <div class="alert"><?= esc($message) ?></div>
    <?php endif; ?>
    <form method="post" class="form-card">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit" class="btn">Cadastrar</button>
    </form>
    <p class="small-text">Já possui conta? <a href="login.php">Faça login</a></p>
</main>
<?php require_once __DIR__ . '/../views/footer.php';
