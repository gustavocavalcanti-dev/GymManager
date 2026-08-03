<?php

declare(strict_types=1);


require_once __DIR__ . '/Database.php';

abstract class Model
{

    protected $db;
    protected $table;
 public function __construct()
    {
        $this->db = Database::conectar();
    }


    public function listarTodos(): array{
        $sql = "SELECT * FROM {$this->table} ";
        $stmp = $this->db->query($sql);
        return $stmp->fetchAll();
    }

    public function buscaPorId($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function cadastrar(array $dados){
        $colunas = array_keys($dados);
        $campos = implode(', ', $colunas);
        $placeholders = implode(', ', array_fill(0, count($colunas), '?'));
        $sql = "INSERT INTO {$this->table} ($campos) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_values($dados));
    }
    public function editar($id, array $dados)
    {
        $campos = [];
        $colunas = array_keys($dados);

        foreach ($colunas as $coluna) {
            $campos[] = "$coluna = ?";
        }

        $set = implode(', ', $campos);
        $sql = "UPDATE {$this->table} SET $set WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $valores = array_values($dados);
        $valores[] = $id;

        return $stmt->execute($valores);
    }

    public function excluir($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
