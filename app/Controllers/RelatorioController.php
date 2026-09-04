<?php

declare(strict_types=1);

class RelatorioController extends Controller
{
    private function periodo(): array
    {
        $de=(string)($_GET['de']??date('Y-01-01'));$ate=(string)($_GET['ate']??date('Y-m-d'));
        if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$de))$de=date('Y-01-01');
        if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$ate))$ate=date('Y-m-d');
        return [$de,$ate];
    }

    private function dados(): array
    {
        [$de,$ate]=$this->periodo();$aluno=new AlunoModel();$prof=new ProfessorModel();$plano=new PlanoModel();$mat=new MatriculaModel();$pag=new PagamentoModel();
        return ['de'=>$de,'ate'=>$ate,'totalAlunos'=>$aluno->contarTotal(),'totalProfessores'=>$prof->contarTotal(),'totalPlanos'=>$plano->contarTotal(),'totalMatriculas'=>$mat->contarTotal(),'alunosAtivos'=>$mat->contarAtivas(),'inadimplentes'=>$pag->contarPendentes(),'totalFaturamento'=>$pag->somarRecebidos($de,$ate),'ticketMedio'=>$pag->ticketMedio($de,$ate),'receitaMensal'=>$pag->receitaPorMes(12,$de,$ate),'novasMatriculas'=>$mat->novasPorMes(12,$de,$ate)];
    }

    public function index(): void {$this->view('Relatorios/index',$this->dados());}

    public function exportarExcel(): void
    {
        $d=$this->dados();$name='relatorio_gymmanager_'.date('Ymd_His').'.xls';header('Content-Type: application/vnd.ms-excel; charset=UTF-8');header('Content-Disposition: attachment; filename="'.$name.'"');echo "\xEF\xBB\xBF";echo "Relatório GymManager\t\n";echo "Período\t{$d['de']} até {$d['ate']}\n";echo "Receita total\tR$ ".number_format((float)$d['totalFaturamento'],2,',','.')."\n";echo "Alunos ativos\t{$d['alunosAtivos']}\n";echo "Inadimplentes\t{$d['inadimplentes']}\n";echo "Ticket médio\tR$ ".number_format((float)$d['ticketMedio'],2,',','.')."\n\n";echo "Mês\tReceita\n";foreach($d['receitaMensal'] as $r)echo $r['mes']."\t".number_format((float)$r['total'],2,',','.')."\n";exit;
    }

    public function imprimir(): void
    {
        $d=$this->dados();
        echo '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><title>Relatório GymManager</title><style>body{font-family:Arial,sans-serif;padding:32px;color:#111}h1{margin:0 0 8px}p{color:#555}.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin:25px 0}.c{border:1px solid #ddd;border-radius:10px;padding:16px}.c b{display:block;font-size:22px;margin-top:8px}@media print{button{display:none}}</style></head><body><h1>Relatório GymManager</h1><p>Período: '.htmlspecialchars($d['de']).' até '.htmlspecialchars($d['ate']).'</p><div class="grid"><div class="c">Receita total<b>R$ '.number_format((float)$d['totalFaturamento'],2,',','.').'</b></div><div class="c">Alunos ativos<b>'.(int)$d['alunosAtivos'].'</b></div><div class="c">Inadimplentes<b>'.(int)$d['inadimplentes'].'</b></div><div class="c">Ticket médio<b>R$ '.number_format((float)$d['ticketMedio'],2,',','.').'</b></div></div><button onclick="window.print()">Imprimir / Salvar como PDF</button><script>window.onload=()=>window.print()</script></body></html>';exit;
    }
}
