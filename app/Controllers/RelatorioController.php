<?php

declare(strict_types=1);

use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Plano;
use App\Models\Professor;

class RelatorioController extends Controller
{
    public function index(): void
    {
        $this->view('Relatorios/index', $this->dados());
    }

    public function exportarExcel(): void
    {
        $d = $this->dados();
        $nome = 'relatorio_gymmanager_' . date('Ymd_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $nome . '"');
        echo "\xEF\xBB\xBF";
        echo "Relatório GymManager\t\n";
        echo "Período\t{$d['de']} até {$d['ate']}\n";
        echo "Receita total\tR$ " . number_format((float)$d['totalFaturamento'], 2, ',', '.') . "\n";
        echo "Alunos ativos\t{$d['alunosAtivos']}\n";
        echo "Inadimplentes\t{$d['inadimplentes']}\n";
        echo "Ticket médio\tR$ " . number_format((float)$d['ticketMedio'], 2, ',', '.') . "\n\n";
        echo "Mês\tReceita\n";

        foreach ($d['receitaMensal'] as $r) {
            echo $r['mes'] . "\t" . number_format((float)$r['total'], 2, ',', '.') . "\n";
        }

        exit;
    }

    public function imprimir(): void
    {
        $d = $this->dados();

        echo '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><title>Relatório GymManager</title>';
        echo '<style>body{font-family:Arial,sans-serif;padding:32px;color:#111}h1{margin:0 0 8px}p{color:#555}.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin:25px 0}.c{border:1px solid #ddd;border-radius:10px;padding:16px}.c b{display:block;font-size:22px;margin-top:8px}@media(max-width:700px){.grid{grid-template-columns:1fr 1fr}}@media print{button{display:none}}</style>';
        echo '</head><body><h1>Relatório GymManager</h1>';
        echo '<p>Período: ' . htmlspecialchars($d['de']) . ' até ' . htmlspecialchars($d['ate']) . '</p>';
        echo '<div class="grid">';
        echo '<div class="c">Receita total<b>R$ ' . number_format((float)$d['totalFaturamento'], 2, ',', '.') . '</b></div>';
        echo '<div class="c">Alunos ativos<b>' . (int)$d['alunosAtivos'] . '</b></div>';
        echo '<div class="c">Inadimplentes<b>' . (int)$d['inadimplentes'] . '</b></div>';
        echo '<div class="c">Ticket médio<b>R$ ' . number_format((float)$d['ticketMedio'], 2, ',', '.') . '</b></div>';
        echo '</div><button onclick="window.print()">Imprimir / Salvar como PDF</button>';
        echo '<script>window.onload=()=>window.print()</script></body></html>';
        exit;
    }

    private function periodo(): array
    {
        $de = (string)($_GET['de'] ?? date('Y-01-01'));
        $ate = (string)($_GET['ate'] ?? date('Y-m-d'));

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $de)) $de = date('Y-01-01');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $ate)) $ate = date('Y-m-d');
        if ($de > $ate) [$de, $ate] = [$ate, $de];

        return [$de, $ate];
    }

    private function dados(): array
    {
        [$de, $ate] = $this->periodo();

        $aluno = new Aluno();
        $professor = new Professor();
        $plano = new Plano();
        $matricula = new Matricula();
        $pagamento = new Pagamento();

        return [
            'de' => $de,
            'ate' => $ate,
            'totalAlunos' => $aluno->contarTotal(),
            'totalProfessores' => $professor->contarTotal(),
            'totalPlanos' => $plano->contarTotal(),
            'totalMatriculas' => $matricula->contarTotal(),
            'alunosAtivos' => $aluno->contarAtivos(),
            'inadimplentes' => $pagamento->contarInadimplentes(),
            'totalFaturamento' => $pagamento->somarRecebidos($de, $ate),
            'ticketMedio' => $pagamento->ticketMedio($de, $ate),
            'receitaMensal' => $pagamento->receitaPorMes(12, $de, $ate),
            'novasMatriculas' => $matricula->novasPorMes(12, $de, $ate),
        ];
    }
}
