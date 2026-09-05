<?php

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected \PDO $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function cadastrar(array $dados): bool
    {
        if ($dados === []) {
            return false;
        }

        $colunas = array_keys($dados);
        $campos = implode(', ', array_map(static fn(string $c): string => "`{$c}`", $colunas));
        $placeholders = implode(', ', array_fill(0, count($colunas), '?'));

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} ({$campos}) VALUES ({$placeholders})"
        );

        return $stmt->execute(array_values($dados));
    }

    public function atualizar(int $id, array $dados): bool
    {
        if ($id <= 0 || $dados === []) {
            return false;
        }

        $campos = array_map(
            static fn(string $c): string => "`{$c}` = ?",
            array_keys($dados)
        );

        $valores = array_values($dados);
        $valores[] = $id;

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET " . implode(', ', $campos) . " WHERE id = ?"
        );

        return $stmt->execute($valores);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /*
     * Compatibilidade com versões anteriores do projeto.
     * Os Controllers finais usam buscarPorId() e atualizar(), que são os nomes
     * definidos no documento técnico da Parte 3.
     */
    public function buscaPorId(int $id): array|false
    {
        return $this->buscarPorId($id);
    }

    public function editar(int $id, array $dados): bool
    {
        return $this->atualizar($id, $dados);
    }
}
