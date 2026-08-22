<?php

declare(strict_types=1);

require_once __DIR__ . '/../Core/Model.php';

use App\Core\Model;
class UsuarioModel extends Model
{
    protected $table = 'usuarios';

    public function buscarPorEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    public function criarUsuario(array $dados): bool
    {
        $dados['senha'] = Security::hashSenha($dados['senha']);
        return $this->cadastrar($dados);
    }

    public function atualizarUltimoLogin(int $id): bool
    {
        $sql = "UPDATE {$this->table} SET ultimo_login = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function alterarSenha(int $id, string $novaSenha): bool
    {
        $hash = Security::hashSenha($novaSenha);
        $sql = "UPDATE {$this->table} SET senha = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$hash, $id]);
    }

    public function alternarStatus(int $id): bool
    {
        $sql = "UPDATE {$this->table} SET ativo = NOT ativo WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function contarTotal(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch();
        return (int) ($resultado['total'] ?? 0);
    }

    public function emailExiste(string $email, ?int $excluirId = null): bool
    {
        if ($excluirId !== null) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE email = ? AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email, $excluirId]);
        } else {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
        }
        $resultado = $stmt->fetch();
        return (int) ($resultado['total'] ?? 0) > 0;
    }
}
