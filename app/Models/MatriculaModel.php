<?php

declare(strict_types=1);

require_once __DIR__ . '/../Core/Model.php';

use App\Core\Model;


class MatriculaModel extends Model
{
    protected $table = 'matriculas';


    public function listarComDetalhes(): array
    {
        $sql = "SELECT m.*, a.nome AS aluno_nome, p.nome AS plano_nome, p.valor AS plano_valor
                FROM {$this->table} m
                INNER JOIN alunos a ON m.aluno_id = a.id
                INNER JOIN planos p ON m.plano_id = p.id
                ORDER BY m.criado_em DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function buscarComDetalhes(int $id): ?array
    {
        $sql = "SELECT m.*, a.nome AS aluno_nome, p.nome AS plano_nome
                FROM {$this->table} m
                INNER JOIN alunos a ON m.aluno_id = a.id
                INNER JOIN planos p ON m.plano_id = p.id
                WHERE m.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }


    public function contarTotal(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch();
        return (int) ($resultado['total'] ?? 0);
    }


    public function contarAtivas(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'ativa'";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch();
        return (int) ($resultado['total'] ?? 0);
    }
}