<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Treino extends Model
{
    protected string $table = 'treinos';

    public function listarComDetalhes(): array
    {
        $sql = "SELECT t.*,
                       a.nome AS aluno_nome,
                       p.nome AS professor_nome
                FROM treinos t
                INNER JOIN alunos a ON a.id = t.aluno_id
                INNER JOIN professores p ON p.id = t.professor_id
                ORDER BY t.data_inicio DESC, t.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function contarTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM treinos");
        return (int)($stmt->fetch()['total'] ?? 0);
    }
}
