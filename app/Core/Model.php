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
    
}
