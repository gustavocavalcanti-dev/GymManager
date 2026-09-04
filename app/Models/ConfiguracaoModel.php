<?php

declare(strict_types=1);
require_once __DIR__ . '/../Core/Model.php';
use App\Core\Model;

class ConfiguracaoModel extends Model
{
    protected $table='configuracoes';

    private function garantirTabela(): void
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS configuracoes (chave VARCHAR(80) PRIMARY KEY, valor TEXT NULL, atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function todos(): array
    {
        $this->garantirTabela();$rows=$this->db->query("SELECT chave,valor FROM configuracoes")->fetchAll();$out=[];foreach($rows as $r)$out[$r['chave']]=$r['valor'];return $out;
    }
    public function salvarVarios(array $dados): void
    {
        $this->garantirTabela();$stmt=$this->db->prepare("INSERT INTO configuracoes(chave,valor) VALUES(?,?) ON DUPLICATE KEY UPDATE valor=VALUES(valor)");foreach($dados as $k=>$v)$stmt->execute([(string)$k,(string)$v]);
    }
}
