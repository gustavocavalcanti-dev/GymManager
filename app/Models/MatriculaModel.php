<?php

declare(strict_types=1);
require_once __DIR__ . '/../Core/Model.php';
use App\Core\Model;

class MatriculaModel extends Model
{
    protected $table='matriculas';

    public function listarComDetalhes(): array
    {
        $sql="SELECT m.*,a.nome aluno_nome,p.nome plano_nome,p.valor plano_valor FROM matriculas m INNER JOIN alunos a ON m.aluno_id=a.id INNER JOIN planos p ON m.plano_id=p.id ORDER BY m.criado_em DESC,m.id DESC";
        return $this->db->query($sql)->fetchAll();
    }
    public function buscarComDetalhes(int $id): ?array
    {
        $stmt=$this->db->prepare("SELECT m.*,a.nome aluno_nome,p.nome plano_nome,p.valor plano_valor FROM matriculas m INNER JOIN alunos a ON m.aluno_id=a.id INNER JOIN planos p ON m.plano_id=p.id WHERE m.id=?");$stmt->execute([$id]);$r=$stmt->fetch();return $r?:null;
    }
    public function contarTotal(): int {return (int)($this->db->query("SELECT COUNT(*) total FROM matriculas")->fetch()['total']??0);}
    public function contarAtivas(): int {return (int)($this->db->query("SELECT COUNT(*) total FROM matriculas WHERE status='ativa'")->fetch()['total']??0);}
    public function novasPorMes(int $meses=7, ?string $de=null, ?string $ate=null): array
    {
        $sql="SELECT DATE_FORMAT(data_inicio,'%Y-%m') chave, ELT(MONTH(data_inicio),'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez') mes, COUNT(*) total FROM matriculas WHERE 1=1";$params=[];
        if($de){$sql.=" AND data_inicio>=?";$params[]=$de;} else {$sql.=" AND data_inicio>=DATE_SUB(CURDATE(), INTERVAL ".max(1,$meses-1)." MONTH)";}
        if($ate){$sql.=" AND data_inicio<=?";$params[]=$ate;}
        $sql.=" GROUP BY chave,mes ORDER BY chave ASC";$stmt=$this->db->prepare($sql);$stmt->execute($params);return $stmt->fetchAll();
    }
    public function planosMaisVendidos(int $limite=5): array
    {
        $limite=max(1,min(10,$limite));
        $sql="SELECT p.nome,COUNT(*) total FROM matriculas m INNER JOIN planos p ON p.id=m.plano_id GROUP BY p.id,p.nome ORDER BY total DESC LIMIT {$limite}";
        return $this->db->query($sql)->fetchAll();
    }
}
