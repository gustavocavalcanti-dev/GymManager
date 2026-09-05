<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Plano extends Model
{
    protected string $table = 'planos';

    public function contarTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM planos");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function listarAtivos(): array
    {
        $stmt = $this->db->query("SELECT * FROM planos WHERE status = 'ativo' ORDER BY valor ASC, nome ASC");
        return $stmt->fetchAll();
    }
}
