<?php

require_once __DIR__ . '/../models/Noticia.php';

class NoticiaController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar(): array
    {
        $noticiaModel = new Noticia($this->pdo);
        return $noticiaModel->listarTodas();
    }

    public function buscar(int $id)
    {
        $noticiaModel = new Noticia($this->pdo);
        return $noticiaModel->buscarPorId($id);
    }

    public function criar(string $titulo, string $noticia, int $autor, ?string $imagem = null): bool
    {
        $noticiaModel = new Noticia($this->pdo);
        return $noticiaModel->criar($titulo, $noticia, $autor, $imagem);
    }

    public function atualizar(int $id, string $titulo, string $noticia, ?string $imagem = null): bool
    {
        $noticiaModel = new Noticia($this->pdo);
        return $noticiaModel->atualizar($id, $titulo, $noticia, $imagem);
    }

    public function excluir(int $id): bool
    {
        $noticiaModel = new Noticia($this->pdo);
        return $noticiaModel->excluir($id);
    }
}
