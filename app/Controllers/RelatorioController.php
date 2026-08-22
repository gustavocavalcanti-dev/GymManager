<?php

declare(strict_types=1);

class RelatorioController extends Controller
{
    public function index(): void
    {
        $alunoModel = new AlunoModel();
        $professorModel = new ProfessorModel();
        $planoModel = new PlanoModel();
        $matriculaModel = new MatriculaModel();
        $pagamentoModel = new PagamentoModel();

        $this->view('Relatorios/index', [
            'totalAlunos' => $alunoModel->contarTotal(),
            'totalProfessores' => $professorModel->contarTotal(),
            'totalPlanos' => $planoModel->contarTotal(),
            'totalMatriculas' => $matriculaModel->contarTotal(),
            'totalFaturamento' => $pagamentoModel->somarRecebidos()
        ]);
    }
}
