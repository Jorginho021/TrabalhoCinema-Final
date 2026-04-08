<?php

$host = 'localhost';
$db = 'projeto_cinema';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $roleColumn = $pdo->query("SHOW COLUMNS FROM usuarios LIKE 'role'")->fetch();
    if (!$roleColumn) {
        $pdo->exec("ALTER TABLE usuarios ADD COLUMN role ENUM('user','admin') NOT NULL DEFAULT 'user' AFTER email");
    }

    $adminExists = $pdo->query("SELECT id FROM usuarios WHERE role = 'admin' LIMIT 1")->fetch();
    if (!$adminExists) {
        $adminEmail = 'admin@admin.com';
        $adminName = 'Administrador';
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);

        $existing = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $existing->execute(['email' => $adminEmail]);

        if ($existing->fetch()) {
            $pdo->prepare("UPDATE usuarios SET role = 'admin' WHERE email = :email")->execute(['email' => $adminEmail]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, role, criado_em) VALUES (:nome, :email, :senha, 'admin', NOW())");
            $stmt->execute(['nome' => $adminName, 'email' => $adminEmail, 'senha' => $adminPassword]);
        }
    }
} catch (PDOException $e) {
    die('Erro de conexão: ' . $e->getMessage());
}
