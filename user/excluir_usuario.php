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
    $auth = new AuthController($pdo);

    if ($auth->excluir($usuarioId)) {
        session_unset();
        session_destroy();
        redirect('../public/index.php');
    }

    flash('Erro ao excluir a conta.');
    redirect('excluir_usuario.php');
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <h1>Excluir conta</h1>
    <p>Tem certeza que deseja excluir a sua conta? Esta ação não pode ser desfeita.</p>
    <form method="post" class="form-card">
        <p id="delete-timer" class="alert">Aguarde <span id="countdown">5</span> segundos para habilitar o botão.</p>
        <button id="confirm-delete" type="submit" class="btn danger" disabled>Confirmar exclusão</button>
        <a href="../private/dashboard.php" class="link-button">Cancelar</a>
    </form>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var countdownElement = document.getElementById('countdown');
        var deleteButton = document.getElementById('confirm-delete');
        var timer = 5;

        var interval = setInterval(function () {
            timer -= 1;
            countdownElement.textContent = timer;

            if (timer <= 0) {
                clearInterval(interval);
                deleteButton.disabled = false;
                document.getElementById('delete-timer').textContent = 'Agora você pode confirmar a exclusão.';
            }
        }, 1000);
    });
</script>
<?php require_once __DIR__ . '/../views/footer.php';
