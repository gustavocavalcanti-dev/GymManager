<?php

declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(): void
    {
        $alunoModel=new AlunoModel();$professorModel=new ProfessorModel();$planoModel=new PlanoModel();$matriculaModel=new MatriculaModel();$pagamentoModel=new PagamentoModel();$treinoModel=new TreinoModel();
        $this->view('Dashboard/index',[
            'totalAlunos'=>$alunoModel->contarTotal(),
            'totalProfessores'=>$professorModel->contarTotal(),
            'totalPlanos'=>$planoModel->contarTotal(),
            'totalMatriculas'=>$matriculaModel->contarTotal(),
            'matriculasAtivas'=>$matriculaModel->contarAtivas(),
            'totalPagamentos'=>$pagamentoModel->somarRecebidos(),
            'receitaMesAtual'=>$pagamentoModel->somarRecebidosMes(),
            'mensalidadesVencidas'=>$pagamentoModel->contarPendentes(),
            'novosAlunosMes'=>$alunoModel->contarNovosMes(),
            'totalTreinos'=>$treinoModel->contarTotal(),
            'receitaMensal'=>$pagamentoModel->receitaPorMes(7),
            'novasMatriculas'=>$matriculaModel->novasPorMes(7),
            'planosMaisVendidos'=>$matriculaModel->planosMaisVendidos(5),
            'ultimosPagamentos'=>$pagamentoModel->listarUltimos(6),
        ]);
    }
}
