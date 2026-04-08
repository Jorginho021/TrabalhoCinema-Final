<?php

require_once __DIR__ . '/../models/Avaliacao.php';

class AvaliacaoController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function avaliar(int $noticiaId, int $usuarioId, int $estrelas): bool
    {
        $avaliacaoModel = new Avaliacao($this->pdo);
        $existente = $avaliacaoModel->buscarPorUsuarioENoticia($usuarioId, $noticiaId);

        if ($existente) {
            return $avaliacaoModel->atualizar($existente['id'], $estrelas);
        }

        return $avaliacaoModel->criar($noticiaId, $usuarioId, $estrelas);
    }
}
