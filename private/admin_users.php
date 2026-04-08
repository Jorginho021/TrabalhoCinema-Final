<?php
require_once __DIR__ . '/../middleware/verifica_login.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../models/Usuario.php';

if (!isAdmin()) {
    flash('Acesso restrito a administradores.');
    redirect('dashboard.php');
}

$userModel = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $role = in_array($_POST['role'] ?? 'user', ['user', 'admin']) ? $_POST['role'] : 'user';

        if ($nome === '' || $email === '' || $senha === '') {
            flash('Preencha nome, email e senha para criar um usuário.');
            redirect('admin_users.php');
        }

        if ($userModel->buscarPorEmail($email)) {
            flash('Email já cadastrado.');
            redirect('admin_users.php');
        }

        if ($userModel->criarComRole($nome, $email, password_hash($senha, PASSWORD_DEFAULT), $role)) {
            flash('Usuário criado com sucesso.');
            redirect('admin_users.php');
        }

        flash('Erro ao criar usuário.');
        redirect('admin_users.php');
    }

    if ($action === 'delete') {
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        if (!$userId || $userId === $_SESSION['usuario_id']) {
            flash('Operação inválida.');
            redirect('admin_users.php');
        }

        if ($userModel->excluir($userId)) {
            flash('Usuário removido com sucesso.');
            redirect('admin_users.php');
        }

        flash('Erro ao remover usuário.');
        redirect('admin_users.php');
    }

    if ($action === 'role') {
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $role = in_array($_POST['role'] ?? 'user', ['user', 'admin']) ? $_POST['role'] : 'user';

        if (!$userId) {
            flash('Operação inválida.');
            redirect('admin_users.php');
        }

        if ($userModel->atualizarRole($userId, $role)) {
            flash('Permissão atualizada com sucesso.');
            redirect('admin_users.php');
        }

        flash('Erro ao atualizar permissão.');
        redirect('admin_users.php');
    }
}

$usuarios = $userModel->listarTodos();

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/navbar.php';
?>
<main class="page-card">
    <h1>Administração de usuários</h1>
    <p>Adicione ou remova pessoas, e altere função de administrador.</p>

    <?php if ($message = getFlashMessage()): ?>
        <div class="alert"><?= esc($message) ?></div>
    <?php endif; ?>

    <section class="form-card admin-create-card">
        <h2>Adicionar novo usuário</h2>
        <form method="post" class="admin-form-grid">
            <input type="hidden" name="action" value="create">

            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>

            <label for="role">Função</label>
            <select id="role" name="role">
                <option value="user">Usuário</option>
                <option value="admin">Administrador</option>
            </select>

            <button type="submit" class="btn success">Criar usuário</button>
        </form>
    </section>

    <section class="admin-table-section">
        <h2>Usuários cadastrados</h2>
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Função</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= esc($usuario['nome']) ?></td>
                            <td><?= esc($usuario['email']) ?></td>
                            <td><?= esc($usuario['role']) ?></td>
                            <td><?= esc(formatDate($usuario['criado_em'])) ?></td>
                            <td>
                                <?php if ($usuario['id'] !== $_SESSION['usuario_id']): ?>
                                <div class="action-group">
                                    <form method="post" class="inline-form">
                                        <input type="hidden" name="action" value="role">
                                        <input type="hidden" name="user_id" value="<?= esc($usuario['id']) ?>">
                                        <select name="role">
                                            <option value="user" <?= $usuario['role'] === 'user' ? 'selected' : '' ?>>Usuário</option>
                                            <option value="admin" <?= $usuario['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                        </select>
                                        <button type="submit" class="btn secondary small">Salvar</button>
                                    </form>
                                    <form method="post" class="inline-form">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= esc($usuario['id']) ?>">
                                        <button type="submit" class="btn danger small">Remover</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                    <span class="muted">Você</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/../views/footer.php';
