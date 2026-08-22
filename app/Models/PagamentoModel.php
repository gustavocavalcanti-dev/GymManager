<?php

declare(strict_types=1);

require_once __DIR__ . '/../Core/Model.php';

use App\Core\Model;


class PagamentoModel extends Model
{
    protected $table = 'pagamentos';


    public function listarComDetalhes(): array
    {
        $sql = "SELECT pg.*, m.aluno_id, a.nome AS aluno_nome, p.nome AS plano_nome
                FROM {$this->table} pg
                INNER JOIN matriculas m ON pg.matricula_id = m.id
                INNER JOIN alunos a ON m.aluno_id = a.id
                INNER JOIN planos p ON m.plano_id = p.id
                ORDER BY pg.criado_em DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function contarTotal(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch();
        return (int) ($resultado['total'] ?? 0);
    }


    public function somarRecebidos(): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) as total FROM {$this->table} WHERE status = 'pago'";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch();
        return (float) ($resultado['total'] ?? 0);
    }
}