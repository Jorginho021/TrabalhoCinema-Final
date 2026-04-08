<?php

class Usuario
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorEmail(string $email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar(string $nome, string $email, string $senha)
    {
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha, criado_em) VALUES (:nome, :email, :senha, NOW())');
        return $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha]);
    }

    public function criarComRole(string $nome, string $email, string $senha, string $role = 'user')
    {
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha, role, criado_em) VALUES (:nome, :email, :senha, :role, NOW())');
        return $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha, 'role' => $role]);
    }

    public function listarTodos(): array
    {
        $stmt = $this->pdo->query('SELECT id, nome, email, role, criado_em FROM usuarios ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarRole(int $id, string $role)
    {
        $stmt = $this->pdo->prepare('UPDATE usuarios SET role = :role WHERE id = :id');
        return $stmt->execute(['role' => $role, 'id' => $id]);
    }

    public function atualizar(int $id, string $nome, string $email, ?string $senha = null)
    {
        if ($senha) {
            $stmt = $this->pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id');
            return $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha, 'id' => $id]);
        }

        $stmt = $this->pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id');
        return $stmt->execute(['nome' => $nome, 'email' => $email, 'id' => $id]);
    }

    public function excluir(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
