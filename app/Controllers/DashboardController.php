<?php

declare(strict_types=1);



class DashboardController extends Controller
{
    public function index(): void
    {
        $alunoModel = new AlunoModel();
        $professorModel = new ProfessorModel();
        $planoModel = new PlanoModel();
        $matriculaModel = new MatriculaModel();
        $pagamentoModel = new PagamentoModel();
        $treinoModel = new TreinoModel();

        $dados = [
            'totalAlunos' => $alunoModel->contarTotal(),
            'totalProfessores' => $professorModel->contarTotal(),
            'totalPlanos' => $planoModel->contarTotal(),
            'totalMatriculas' => $matriculaModel->contarTotal(),
            'matriculasAtivas' => $matriculaModel->contarAtivas(),
            'totalPagamentos' => $pagamentoModel->somarRecebidos(),
            'totalTreinos' => $treinoModel->contarTotal()
        ];

        $this->view('Dashboard/index', $dados);
    }
}
