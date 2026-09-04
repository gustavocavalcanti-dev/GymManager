<?php

declare(strict_types=1);

require_once __DIR__ . '/../Core/Model.php';

use App\Core\Model;

class TreinoModel extends Model
{
    protected $table = 'treinos';

    public function listarComDetalhes(): array
    {
        $sql = "SELECT t.*, a.nome AS aluno_nome, p.nome AS professor_nome
                FROM {$this->table} t
                INNER JOIN alunos a ON t.aluno_id = a.id
                INNER JOIN professores p ON t.professor_id = p.id
                ORDER BY t.criado_em DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function contarTotal(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch();
        return (int)($resultado['total'] ?? 0);
    }
}