<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$scriptPath = $_SERVER['SCRIPT_NAME'];
$publicPrefix = '';
$privatePrefix = 'private/';
$userPrefix = 'user/';
$logoutPath = 'public/logout.php';

if (strpos($scriptPath, '/public/') !== false) {
    $publicPrefix = '';
    $privatePrefix = '../private/';
    $userPrefix = '../user/';
    $logoutPath = 'logout.php';
} elseif (strpos($scriptPath, '/private/') !== false) {
    $publicPrefix = '../public/';
    $privatePrefix = '';
    $userPrefix = '../user/';
    $logoutPath = '../public/logout.php';
} elseif (strpos($scriptPath, '/user/') !== false) {
    $publicPrefix = '../public/';
    $privatePrefix = '../private/';
    $userPrefix = '';
    $logoutPath = '../public/logout.php';
} else {
    $publicPrefix = 'public/';
    $privatePrefix = 'private/';
    $userPrefix = 'user/';
    $logoutPath = 'public/logout.php';
}
?>
<nav class="main-nav">
    <div class="nav-container">
        <a class="brand" href="<?= esc($publicPrefix) ?>index.php">Cinema News</a>
        <ul>
            <li><a href="<?= esc($publicPrefix) ?>index.php">Home</a></li>
            <li><a href="<?= esc($publicPrefix) ?>noticia.php">Notícias</a></li>
            <?php if (!empty($_SESSION['usuario_id'])): ?>
                <li><a href="<?= esc($privatePrefix) ?>dashboard.php">Dashboard</a></li>
                <?php if (!empty($_SESSION['usuario_role']) && $_SESSION['usuario_role'] === 'admin'): ?>
                    <li><a href="<?= esc($privatePrefix) ?>admin_users.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="<?= esc($privatePrefix) ?>nova_noticia.php">Nova notícia</a></li>
                <li><a href="<?= esc($userPrefix) ?>editar_usuario.php">Perfil</a></li>
                <li><a href="<?= esc($logoutPath) ?>">Sair</a></li>
            <?php else: ?>
                <li><a href="<?= esc($publicPrefix) ?>login.php">Login</a></li>
                <li><a href="<?= esc($publicPrefix) ?>cadastro.php">Cadastro</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
