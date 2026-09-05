<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Pagamento extends Model
{
    protected string $table = 'pagamentos';

    public function listarComDetalhes(): array
    {
        $this->atualizarAtrasados();

        $sql = "SELECT pg.*,
                       m.aluno_id,
                       a.nome AS aluno_nome,
                       p.nome AS plano_nome
                FROM pagamentos pg
                INNER JOIN matriculas m ON m.id = pg.matricula_id
                INNER JOIN alunos a ON a.id = m.aluno_id
                INNER JOIN planos p ON p.id = m.plano_id
                ORDER BY pg.data_vencimento DESC, pg.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function listarUltimos(int $limite = 6): array
    {
        $this->atualizarAtrasados();
        $limite = max(1, min(20, $limite));

        $sql = "SELECT pg.*,
                       a.nome AS aluno_nome,
                       p.nome AS plano_nome
                FROM pagamentos pg
                INNER JOIN matriculas m ON m.id = pg.matricula_id
                INNER JOIN alunos a ON a.id = m.aluno_id
                INNER JOIN planos p ON p.id = m.plano_id
                ORDER BY COALESCE(pg.data_pagamento, pg.data_vencimento) DESC, pg.id DESC
                LIMIT {$limite}";

        return $this->db->query($sql)->fetchAll();
    }

    public function somarRecebidos(?string $de = null, ?string $ate = null): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) AS total
                FROM pagamentos
                WHERE status = 'pago'";
        $params = [];

        if ($de !== null) {
            $sql .= " AND data_pagamento >= ?";
            $params[] = $de;
        }

        if ($ate !== null) {
            $sql .= " AND data_pagamento <= ?";
            $params[] = $ate;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (float)($stmt->fetch()['total'] ?? 0);
    }

    public function somarRecebidosMes(): float
    {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(valor), 0) AS total
             FROM pagamentos
             WHERE status = 'pago'
               AND YEAR(data_pagamento) = YEAR(CURDATE())
               AND MONTH(data_pagamento) = MONTH(CURDATE())"
        );

        return (float)($stmt->fetch()['total'] ?? 0);
    }

    public function contarInadimplentes(): int
    {
        $this->atualizarAtrasados();

        $stmt = $this->db->query(
            "SELECT COUNT(DISTINCT m.aluno_id) AS total
             FROM pagamentos pg
             INNER JOIN matriculas m ON m.id = pg.matricula_id
             WHERE pg.status = 'atrasado'"
        );

        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function contarMensalidadesVencidas(): int
    {
        $this->atualizarAtrasados();
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM pagamentos WHERE status = 'atrasado'");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function ticketMedio(?string $de = null, ?string $ate = null): float
    {
        $sql = "SELECT COALESCE(AVG(valor), 0) AS total
                FROM pagamentos
                WHERE status = 'pago'";
        $params = [];

        if ($de !== null) {
            $sql .= " AND data_pagamento >= ?";
            $params[] = $de;
        }

        if ($ate !== null) {
            $sql .= " AND data_pagamento <= ?";
            $params[] = $ate;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (float)($stmt->fetch()['total'] ?? 0);
    }

    public function receitaPorMes(int $meses = 7, ?string $de = null, ?string $ate = null): array
    {
        $sql = "SELECT DATE_FORMAT(data_pagamento, '%Y-%m') AS chave,
                       ELT(MONTH(data_pagamento),'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez') AS mes,
                       COALESCE(SUM(valor), 0) AS total
                FROM pagamentos
                WHERE status = 'pago'";
        $params = [];

        if ($de !== null) {
            $sql .= " AND data_pagamento >= ?";
            $params[] = $de;
        } else {
            $sql .= " AND data_pagamento >= DATE_SUB(CURDATE(), INTERVAL " . max(1, $meses - 1) . " MONTH)";
        }

        if ($ate !== null) {
            $sql .= " AND data_pagamento <= ?";
            $params[] = $ate;
        }

        $sql .= " GROUP BY chave, mes ORDER BY chave ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function atualizarAtrasados(): void
    {
        $this->db->exec(
            "UPDATE pagamentos
             SET status = 'atrasado'
             WHERE status = 'pendente'
               AND data_vencimento < CURDATE()"
        );

        $this->db->exec(
            "UPDATE pagamentos
             SET status = 'pendente'
             WHERE status = 'atrasado'
               AND data_vencimento >= CURDATE()"
        );
    }
}
