<?php

class Avaliacao
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorUsuarioENoticia(int $usuarioId, int $noticiaId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM avaliacoes WHERE usuario_id = :usuario_id AND noticia_id = :noticia_id');
        $stmt->execute(['usuario_id' => $usuarioId, 'noticia_id' => $noticiaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarPorNoticia(int $noticiaId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, u.nome AS usuario_nome
             FROM avaliacoes a
             JOIN usuarios u ON u.id = a.usuario_id
             WHERE a.noticia_id = :noticia_id
             ORDER BY a.criado_em DESC'
        );
        $stmt->execute(['noticia_id' => $noticiaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar(int $noticiaId, int $usuarioId, int $estrelas)
    {
        $stmt = $this->pdo->prepare('INSERT INTO avaliacoes (noticia_id, usuario_id, estrelas, criado_em) VALUES (:noticia_id, :usuario_id, :estrelas, NOW())');
        return $stmt->execute(['noticia_id' => $noticiaId, 'usuario_id' => $usuarioId, 'estrelas' => $estrelas]);
    }

    public function atualizar(int $id, int $estrelas)
    {
        $stmt = $this->pdo->prepare('UPDATE avaliacoes SET estrelas = :estrelas WHERE id = :id');
        return $stmt->execute(['estrelas' => $estrelas, 'id' => $id]);
    }
}
