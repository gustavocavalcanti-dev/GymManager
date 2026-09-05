<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Usuario extends Model
{
    protected string $table = 'usuarios';

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

    public function criarUsuario(array $dados): bool
    {
        $dados['senha'] = \Security::hashSenha((string)$dados['senha']);
        return $this->cadastrar($dados);
    }

    public function alterarSenha(int $id, string $novaSenha): bool
    {
        $hash = \Security::hashSenha($novaSenha);
        $stmt = $this->db->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function alternarStatus(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE usuarios
             SET status = CASE WHEN status = 'ativo' THEN 'inativo' ELSE 'ativo' END
             WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }

    public function contarTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM usuarios");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function emailExiste(string $email, ?int $ignorarId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE email = ?";
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
