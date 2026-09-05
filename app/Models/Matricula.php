<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Matricula extends Model
{
    protected string $table = 'matriculas';

    public function listarComDetalhes(): array
    {
        $this->atualizarVencidas();

        $sql = "SELECT m.*,
                       a.nome AS aluno_nome,
                       p.nome AS plano_nome,
                       p.valor AS plano_valor
                FROM matriculas m
                INNER JOIN alunos a ON a.id = m.aluno_id
                INNER JOIN planos p ON p.id = m.plano_id
                ORDER BY m.criado_em DESC, m.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function buscarComDetalhes(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT m.*,
                    a.nome AS aluno_nome,
                    p.nome AS plano_nome,
                    p.valor AS plano_valor
             FROM matriculas m
             INNER JOIN alunos a ON a.id = m.aluno_id
             INNER JOIN planos p ON p.id = m.plano_id
             WHERE m.id = ?"
        );
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

    public function contarTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM matriculas");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function contarAtivas(): int
    {
        $this->atualizarVencidas();
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM matriculas WHERE status = 'ativa'");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function novasPorMes(int $meses = 7, ?string $de = null, ?string $ate = null): array
    {
        $sql = "SELECT DATE_FORMAT(data_inicio, '%Y-%m') AS chave,
                       ELT(MONTH(data_inicio),'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez') AS mes,
                       COUNT(*) AS total
                FROM matriculas
                WHERE 1 = 1";
        $params = [];

        if ($de !== null) {
            $sql .= " AND data_inicio >= ?";
            $params[] = $de;
        } else {
            $sql .= " AND data_inicio >= DATE_SUB(CURDATE(), INTERVAL " . max(1, $meses - 1) . " MONTH)";
        }

        if ($ate !== null) {
            $sql .= " AND data_inicio <= ?";
            $params[] = $ate;
        }

        $sql .= " GROUP BY chave, mes ORDER BY chave ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function planosMaisVendidos(int $limite = 5): array
    {
        $limite = max(1, min(10, $limite));

        $sql = "SELECT p.nome, COUNT(*) AS total
                FROM matriculas m
                INNER JOIN planos p ON p.id = m.plano_id
                GROUP BY p.id, p.nome
                ORDER BY total DESC
                LIMIT {$limite}";

        return $this->db->query($sql)->fetchAll();
    }

    public function atualizarVencidas(): void
    {
        $this->db->exec(
            "UPDATE matriculas
             SET status = 'vencida'
             WHERE status = 'ativa'
               AND data_fim < CURDATE()"
        );
    }
}
