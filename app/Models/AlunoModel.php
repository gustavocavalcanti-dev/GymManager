<?php

namespace App\Models;

use App\Core\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

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
            "INSERT INTO {$this->table} (nome, email) VALUES (?, ?)",
            [$data['nome'], $data['email']]
        );
    }

    public function update($id, $data)
    {
        return $this->db->query(
            "UPDATE {$this->table} SET nome = ?, email = ? WHERE id = ?",
            [$data['nome'], $data['email'], $id]
        );
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
} 