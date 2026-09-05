<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Aluno extends Model
{
    protected string $table = 'alunos';

    public function contarTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM alunos");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function contarAtivos(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM alunos WHERE status = 'ativo'");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function contarNovosMes(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) AS total
             FROM alunos
             WHERE YEAR(criado_em) = YEAR(CURDATE())
               AND MONTH(criado_em) = MONTH(CURDATE())"
        );
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function listarComPlanoStatus(): array
    {
        $sql = "SELECT a.*,
                       p.nome AS plano_nome,
                       m.status AS matricula_status
                FROM alunos a
                LEFT JOIN matriculas m ON m.id = (
                    SELECT m2.id
                    FROM matriculas m2
                    WHERE m2.aluno_id = a.id
                    ORDER BY m2.id DESC
                    LIMIT 1
                )
                LEFT JOIN planos p ON p.id = m.plano_id
                ORDER BY a.criado_em DESC, a.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function cpfExiste(string $cpf, ?int $ignorarId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM alunos WHERE cpf = ?";
        $params = [$cpf];

        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $params[] = $ignorarId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int)($stmt->fetch()['total'] ?? 0) > 0;
    }

    public function emailExiste(string $email, ?int $ignorarId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM alunos WHERE email = ?";
        $params = [$email];

        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $params[] = $ignorarId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int)($stmt->fetch()['total'] ?? 0) > 0;
    }
}
