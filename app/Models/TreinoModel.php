<?php

namespace App\Models;

use App\Core\Model;

class Treino extends Model
{
    protected $table = 'treinos';

    public function all()
    {
        return $this->db->query("SELECT * FROM {$this->table}");
    }

    public function find($id)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }

    public function create($data)
    {
        return $this->db->query(
            "INSERT INTO {$this->table} (descricao, usuario_id) VALUES (?, ?)",
            [$data['descricao'], $data['usuario_id']]
        );
    }

    public function update($id, $data)
    {
        return $this->db->query(
            "UPDATE {$this->table} SET descricao = ?, usuario_id = ? WHERE id = ?",
            [$data['descricao'], $data['usuario_id'], $id]
        );
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
}