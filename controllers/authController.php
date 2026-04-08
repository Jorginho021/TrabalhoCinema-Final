<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function login(string $email, string $senha): bool
    {
        $usuarioModel = new Usuario($this->pdo);
        $usuario = $usuarioModel->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_role'] = $usuario['role'] ?? 'user';
            return true;
        }

        return false;
    }

    public function cadastrar(string $nome, string $email, string $senha): bool
    {
        $usuarioModel = new Usuario($this->pdo);

        if ($usuarioModel->buscarPorEmail($email)) {
            return false;
        }

        return $usuarioModel->criar($nome, $email, password_hash($senha, PASSWORD_DEFAULT));
    }

    public function atualizar(int $id, string $nome, string $email, ?string $senha = null): bool
    {
        $usuarioModel = new Usuario($this->pdo);
        $usuarioExistente = $usuarioModel->buscarPorEmail($email);

        if ($usuarioExistente && $usuarioExistente['id'] !== $id) {
            return false;
        }

        $senhaHash = $senha ? password_hash($senha, PASSWORD_DEFAULT) : null;
        return $usuarioModel->atualizar($id, $nome, $email, $senhaHash);
    }

    public function excluir(int $id): bool
    {
        $usuarioModel = new Usuario($this->pdo);
        return $usuarioModel->excluir($id);
    }
}
