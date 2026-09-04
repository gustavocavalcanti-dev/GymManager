<?php

declare(strict_types=1);
require_once __DIR__ . '/../Core/Model.php';
use App\Core\Model;

class PagamentoModel extends Model
{
    protected $table = 'pagamentos';

    public function listarComDetalhes(): array
    {
        $sql = "SELECT pg.*, m.aluno_id, a.nome AS aluno_nome, p.nome AS plano_nome
                FROM pagamentos pg
                INNER JOIN matriculas m ON pg.matricula_id=m.id
                INNER JOIN alunos a ON m.aluno_id=a.id
                INNER JOIN planos p ON m.plano_id=p.id
                ORDER BY pg.data_pagamento DESC, pg.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarUltimos(int $limite=6): array
    {
        $limite=max(1,min(20,$limite));
        $sql = "SELECT pg.*, a.nome aluno_nome, p.nome plano_nome
                FROM pagamentos pg
                INNER JOIN matriculas m ON pg.matricula_id=m.id
                INNER JOIN alunos a ON m.aluno_id=a.id
                INNER JOIN planos p ON m.plano_id=p.id
                ORDER BY pg.data_pagamento DESC, pg.id DESC LIMIT {$limite}";
        return $this->db->query($sql)->fetchAll();
    }

    public function contarTotal(): int
    {
        return (int)($this->db->query("SELECT COUNT(*) total FROM pagamentos")->fetch()['total'] ?? 0);
    }

    public function somarRecebidos(?string $de=null, ?string $ate=null): float
    {
        $sql="SELECT COALESCE(SUM(valor),0) total FROM pagamentos WHERE status='pago'";
        $params=[];
        if($de){$sql.=" AND data_pagamento>=?";$params[]=$de;}
        if($ate){$sql.=" AND data_pagamento<=?";$params[]=$ate;}
        $stmt=$this->db->prepare($sql);$stmt->execute($params);
        return (float)($stmt->fetch()['total'] ?? 0);
    }

    public function somarRecebidosMes(): float
    {
        $stmt=$this->db->query("SELECT COALESCE(SUM(valor),0) total FROM pagamentos WHERE status='pago' AND YEAR(data_pagamento)=YEAR(CURDATE()) AND MONTH(data_pagamento)=MONTH(CURDATE())");
        return (float)($stmt->fetch()['total'] ?? 0);
    }

    public function contarPendentes(): int
    {
        $stmt=$this->db->query("SELECT COUNT(*) total FROM pagamentos WHERE status IN ('pendente','cancelado')");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function ticketMedio(?string $de=null, ?string $ate=null): float
    {
        $sql="SELECT COALESCE(AVG(valor),0) total FROM pagamentos WHERE status='pago'";$params=[];
        if($de){$sql.=" AND data_pagamento>=?";$params[]=$de;} if($ate){$sql.=" AND data_pagamento<=?";$params[]=$ate;}
        $stmt=$this->db->prepare($sql);$stmt->execute($params); return (float)($stmt->fetch()['total']??0);
    }

    public function receitaPorMes(int $meses=7, ?string $de=null, ?string $ate=null): array
    {
        $sql="SELECT DATE_FORMAT(data_pagamento,'%Y-%m') chave, ELT(MONTH(data_pagamento),'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez') mes, COALESCE(SUM(valor),0) total
              FROM pagamentos WHERE status='pago'";$params=[];
        if($de){$sql.=" AND data_pagamento>=?";$params[]=$de;} else {$sql.=" AND data_pagamento>=DATE_SUB(CURDATE(), INTERVAL ".max(1,$meses-1)." MONTH)";}
        if($ate){$sql.=" AND data_pagamento<=?";$params[]=$ate;}
        $sql.=" GROUP BY chave, mes ORDER BY chave ASC";
        $stmt=$this->db->prepare($sql);$stmt->execute($params); return $stmt->fetchAll();
    }
}
