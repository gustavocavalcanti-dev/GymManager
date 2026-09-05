<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Professor extends Model
{
    protected string $table = 'professores';

    public function contarTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM professores");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function cpfExiste(string $cpf, ?int $ignorarId = null): bool
    {
        return $this->campoExiste('cpf', $cpf, $ignorarId);
    }

    public function emailExiste(string $email, ?int $ignorarId = null): bool
    {
        return $this->campoExiste('email', $email, $ignorarId);
    }

    public function crefExiste(string $cref, ?int $ignorarId = null): bool
    {
        if ($cref === '') {
            return false;
        }

        return $this->campoExiste('cref', $cref, $ignorarId);
    }

    private function campoExiste(string $campo, string $valor, ?int $ignorarId): bool
    {
        $permitidos = ['cpf', 'email', 'cref'];

        if (!in_array($campo, $permitidos, true)) {
            return false;
        }

        $sql = "SELECT COUNT(*) AS total FROM professores WHERE {$campo} = ?";
        $params = [$valor];

        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $params[] = $ignorarId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int)($stmt->fetch()['total'] ?? 0) > 0;
    }
}
