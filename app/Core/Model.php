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
        $stmp = $this->db->query($sql);
        $stmp->execute([$id]);
        return $stmp->fetch();
    }

    public function cadastrar(array $dados){
        $colunas = array_keys($dados);
        $campos = implode(', ', $colunas);
        $placeholders = implode(', ', array_fill(0, count($colunas), '?'));
        $sql = "INSERT INTO {$this->table} ($campos) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_values($dados));
    }
    
}
