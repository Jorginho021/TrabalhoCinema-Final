<?php

class Noticia
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarTodas(): array
    {
        $stmt = $this->pdo->query(
            'SELECT n.*, u.nome AS autor_nome, COALESCE(AVG(a.estrelas), 0) AS media_avaliacao, COUNT(a.id) AS total_avaliacoes
             FROM noticias n
             JOIN usuarios u ON u.id = n.autor
             LEFT JOIN avaliacoes a ON a.noticia_id = n.id
             GROUP BY n.id
             ORDER BY n.data DESC'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id)
    {
        $stmt = $this->pdo->prepare(
            'SELECT n.*, u.nome AS autor_nome, COALESCE(AVG(a.estrelas), 0) AS media_avaliacao, COUNT(a.id) AS total_avaliacoes
             FROM noticias n
             JOIN usuarios u ON u.id = n.autor
             LEFT JOIN avaliacoes a ON a.noticia_id = n.id
             WHERE n.id = :id
             GROUP BY n.id'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar(string $titulo, string $noticia, int $autor, ?string $imagem = null)
    {
        $stmt = $this->pdo->prepare('INSERT INTO noticias (titulo, noticia, data, autor, imagem) VALUES (:titulo, :noticia, NOW(), :autor, :imagem)');
        return $stmt->execute(['titulo' => $titulo, 'noticia' => $noticia, 'autor' => $autor, 'imagem' => $imagem]);
    }

    public function atualizar(int $id, string $titulo, string $noticia, ?string $imagem = null)
    {
        $stmt = $this->pdo->prepare('UPDATE noticias SET titulo = :titulo, noticia = :noticia, imagem = :imagem WHERE id = :id');
        return $stmt->execute(['titulo' => $titulo, 'noticia' => $noticia, 'imagem' => $imagem, 'id' => $id]);
    }

    public function excluir(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM noticias WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function listarPorAutor(int $autorId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT n.*, u.nome AS autor_nome, COALESCE(AVG(a.estrelas), 0) AS media_avaliacao, COUNT(a.id) AS total_avaliacoes
             FROM noticias n
             JOIN usuarios u ON u.id = n.autor
             LEFT JOIN avaliacoes a ON a.noticia_id = n.id
             WHERE n.autor = :autor
             GROUP BY n.id
             ORDER BY n.data DESC'
        );
        $stmt->execute(['autor' => $autorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorEstrelas(int $estrelas): array
    {
        if ($estrelas === 5) {
            $query =
                'SELECT n.*, u.nome AS autor_nome, AVG(a.estrelas) AS media_avaliacao, COUNT(a.id) AS total_avaliacoes
                 FROM noticias n
                 JOIN usuarios u ON u.id = n.autor
                 JOIN avaliacoes a ON a.noticia_id = n.id
                 GROUP BY n.id
                 HAVING AVG(a.estrelas) = 5
                 ORDER BY n.data DESC';
            $stmt = $this->pdo->prepare($query);
        } else {
            $query =
                'SELECT n.*, u.nome AS autor_nome, AVG(a.estrelas) AS media_avaliacao, COUNT(a.id) AS total_avaliacoes
                 FROM noticias n
                 JOIN usuarios u ON u.id = n.autor
                 JOIN avaliacoes a ON a.noticia_id = n.id
                 GROUP BY n.id
                 HAVING AVG(a.estrelas) >= :estrelas AND AVG(a.estrelas) < :next
                 ORDER BY n.data DESC';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['estrelas' => $estrelas, 'next' => $estrelas + 1]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTopAvaliadas(int $limite = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT n.*, u.nome AS autor_nome, COALESCE(AVG(a.estrelas), 0) AS media_avaliacao, COUNT(a.id) AS total_avaliacoes
             FROM noticias n
             JOIN usuarios u ON u.id = n.autor
             LEFT JOIN avaliacoes a ON a.noticia_id = n.id
             GROUP BY n.id
             ORDER BY media_avaliacao DESC, n.data DESC
             LIMIT :limite'
        );
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
