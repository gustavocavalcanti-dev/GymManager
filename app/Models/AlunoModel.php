<?php
declare(strict_types=1);

require_once __DIR__ . '/../Core/Model.php';
use App\Core\Model;

class AlunoModel extends Model
{
    protected $table = 'alunos';

    public function contarTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) total FROM {$this->table}");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function contarNovosMes(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) total FROM {$this->table} WHERE YEAR(criado_em)=YEAR(CURDATE()) AND MONTH(criado_em)=MONTH(CURDATE())");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function listarComPlanoStatus(): array
    {
        $sql = "SELECT a.*,
                       p.nome AS plano_nome,
                       CASE
                           WHEN m.id IS NULL THEN 'inativo'
                           WHEN m.status = 'ativa' THEN 'ativo'
                           WHEN m.status = 'pendente' THEN 'pendente'
                           ELSE 'inativo'
                       END AS status
                FROM alunos a
                LEFT JOIN matriculas m ON m.id = (
                    SELECT m2.id FROM matriculas m2 WHERE m2.aluno_id = a.id ORDER BY m2.id DESC LIMIT 1
                )
                LEFT JOIN planos p ON p.id = m.plano_id
                ORDER BY a.criado_em DESC, a.id DESC";
        try {
            return $this->db->query($sql)->fetchAll();
        } catch (\Throwable) {
            return $this->listarTodos();
        }
    }
}
