<?php

declare(strict_types=1);

use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Plano;
use App\Models\Professor;
use App\Models\Treino;

class DashboardController extends Controller
{
    public function index(): void
    {
        $aluno = new Aluno();
        $professor = new Professor();
        $plano = new Plano();
        $matricula = new Matricula();
        $pagamento = new Pagamento();
        $treino = new Treino();

        $this->view('Dashboard/index', [
            'totalAlunos' => $aluno->contarTotal(),
            'totalProfessores' => $professor->contarTotal(),
            'totalPlanos' => $plano->contarTotal(),
            'totalMatriculas' => $matricula->contarTotal(),
            'matriculasAtivas' => $matricula->contarAtivas(),
            'totalPagamentos' => $pagamento->somarRecebidos(),
            'receitaMesAtual' => $pagamento->somarRecebidosMes(),
            'mensalidadesVencidas' => $pagamento->contarMensalidadesVencidas(),
            'novosAlunosMes' => $aluno->contarNovosMes(),
            'totalTreinos' => $treino->contarTotal(),
            'receitaMensal' => $pagamento->receitaPorMes(7),
            'novasMatriculas' => $matricula->novasPorMes(7),
            'planosMaisVendidos' => $matricula->planosMaisVendidos(5),
            'ultimosPagamentos' => $pagamento->listarUltimos(6),
        ]);
    }
}
