<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';

$receitaMesAtual = (float)($receitaMesAtual ?? $totalPagamentos ?? 0);
$mensalidadesVencidas = (int)($mensalidadesVencidas ?? 0);
$novosAlunosMes = (int)($novosAlunosMes ?? 0);
$receitaMensal = $receitaMensal ?? [];
$novasMatriculas = $novasMatriculas ?? [];
$planosMaisVendidos = $planosMaisVendidos ?? [];
$ultimosPagamentos = $ultimosPagamentos ?? [];
$totalMatriculas = (int)($totalMatriculas ?? 0);
$mesesPt = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];
$mesAnoAtual = ($mesesPt[(int)date('n')] ?? date('F')) . ' de ' . date('Y');

function gmLineChart(array $values, array $labels): string {
    $w=680; $h=240; $left=55; $right=18; $top=18; $bottom=32;
    $plotW=$w-$left-$right; $plotH=$h-$top-$bottom;
    $max=max(1, ...array_map('floatval', $values ?: [1]));
    $count=max(1,count($values));
    $pts=[];
    foreach ($values as $i=>$v) {
        $x=$left + ($count===1 ? $plotW/2 : ($plotW*$i/($count-1)));
        $y=$top + $plotH - ((float)$v/$max*$plotH*.9);
        $pts[]=[round($x,1),round($y,1)];
    }
    if (!$pts) $pts=[[$left,$top+$plotH]];
    $poly=implode(' ', array_map(fn($p)=>$p[0].','.$p[1],$pts));
    $area=$left.','.($top+$plotH).' '.$poly.' '.($left+$plotW).','.($top+$plotH);
    ob_start(); ?>
    <svg class="chart-svg" viewBox="0 0 <?= $w ?> <?= $h ?>" preserveAspectRatio="none" aria-hidden="true">
        <?php for($i=0;$i<=4;$i++): $y=$top+$plotH*($i/4); $val=$max*(1-$i/4); ?>
            <line class="chart-grid-line" x1="<?= $left ?>" y1="<?= $y ?>" x2="<?= $left+$plotW ?>" y2="<?= $y ?>"/>
            <text class="chart-axis-label" x="0" y="<?= $y+4 ?>">R$ <?= number_format($val/1000,0,',','.') ?>k</text>
        <?php endfor; ?>
        <polygon class="chart-area" points="<?= $area ?>"/>
        <polyline class="chart-line" points="<?= $poly ?>"/>
        <?php foreach($pts as $i=>$p): ?>
            <circle cx="<?= $p[0] ?>" cy="<?= $p[1] ?>" r="3" fill="#2164e9"/>
            <text class="chart-axis-label" x="<?= $p[0]-9 ?>" y="<?= $h-8 ?>"><?= htmlspecialchars((string)($labels[$i] ?? '')) ?></text>
        <?php endforeach; ?>
    </svg>
    <?php return (string)ob_get_clean();
}

$months = array_column($receitaMensal, 'mes');
$receitas = array_map(fn($r)=>(float)$r['total'], $receitaMensal);
$matMonths = array_column($novasMatriculas, 'mes');
$matValues = array_map(fn($r)=>(int)$r['total'], $novasMatriculas);

$colors=['#2164e9','#32c360','#f6a416','#9164db','#ef5da8'];
$totalPlanSales=array_sum(array_map(fn($p)=>(int)$p['total'],$planosMaisVendidos));
$angle=0; $segments=[];
foreach($planosMaisVendidos as $i=>$p){
    $pct=$totalPlanSales>0?((int)$p['total']/$totalPlanSales*100):0;
    $segments[]=$colors[$i%count($colors)].' '.$angle.'% '.($angle+$pct).'%';
    $angle+=$pct;
}
if(!$segments) $segments=['#e5e7eb 0% 100%'];
?>

<div class="page-head">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="subtitle">Visão geral da sua academia — <?= htmlspecialchars($mesAnoAtual) ?></p>
    </div>
    <?php if (($usuarioLogado['perfil'] ?? '') === 'administrador'): ?>
    <div class="page-actions">
        <a class="btn btn-secondary" href="<?= $basePath ?>/relatorios/exportar-excel"><?= UI::icon('download',18) ?> Exportar</a>
    </div>
    <?php endif; ?>
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">Total de alunos</div><div class="stat-value"><?= number_format((int)$totalAlunos, 0, '', '.') ?></div><div class="stat-change up">↗ cadastro total</div><div class="stat-icon"><?= UI::icon('users',22) ?></div></div>
    <div class="stat-card"><div class="stat-label">Professores</div><div class="stat-value"><?= (int)$totalProfessores ?></div><div class="stat-change up">↗ equipe cadastrada</div><div class="stat-icon"><?= UI::icon('teacher',22) ?></div></div>
    <div class="stat-card"><div class="stat-label">Receita mensal</div><div class="stat-value">R$ <?= number_format($receitaMesAtual,2,',','.') ?></div><div class="stat-change up">↗ pagamentos do mês</div><div class="stat-icon green"><?= UI::icon('money',22) ?></div></div>
    <div class="stat-card"><div class="stat-label">Matrículas ativas</div><div class="stat-value"><?= (int)$matriculasAtivas ?></div><div class="stat-change up">↗ de <?= (int)$totalMatriculas ?> matrículas</div><div class="stat-icon"><?= UI::icon('clipboard',22) ?></div></div>
    <div class="stat-card"><div class="stat-label">Mensalidades vencidas</div><div class="stat-value"><?= $mensalidadesVencidas ?></div><div class="stat-change <?= $mensalidadesVencidas ? 'down' : 'up' ?>"><?= $mensalidadesVencidas ? '↘ requer atenção' : '↗ nenhuma pendência' ?></div><div class="stat-icon red"><?= UI::icon('alert',22) ?></div></div>
    <div class="stat-card"><div class="stat-label">Novos alunos (mês)</div><div class="stat-value"><?= $novosAlunosMes ?></div><div class="stat-change up">↗ novos cadastros</div><div class="stat-icon green"><?= UI::icon('user-plus',22) ?></div></div>
</div>

<div class="dashboard-row">
    <section class="chart-card">
        <h3>Receita mensal</h3>
        <?= gmLineChart($receitas,$months) ?>
    </section>
    <section class="chart-card">
        <h3>Planos mais vendidos</h3>
        <div class="donut-wrap">
            <div class="donut" style="background:conic-gradient(<?= htmlspecialchars(implode(',',$segments)) ?>)"></div>
            <div class="legend">
                <?php foreach($planosMaisVendidos as $i=>$p): ?>
                    <span class="legend-item"><i class="legend-dot" style="background:<?= $colors[$i%count($colors)] ?>"></i><?= htmlspecialchars((string)$p['nome']) ?></span>
                <?php endforeach; ?>
                <?php if(!$planosMaisVendidos): ?><span class="legend-item">Sem dados ainda</span><?php endif; ?>
            </div>
        </div>
    </section>
</div>

<div class="dashboard-row bottom">
    <section class="chart-card">
        <h3>Novas matrículas</h3>
        <?php $maxMat=max(1,...($matValues?:[1])); ?>
        <div class="bar-chart">
            <?php foreach($matValues as $i=>$v): ?>
                <div class="bar-group"><div class="bar" style="height:<?= max(4,($v/$maxMat*88)) ?>%"></div><span class="bar-label"><?= htmlspecialchars((string)($matMonths[$i] ?? '')) ?></span></div>
            <?php endforeach; ?>
            <?php if(!$matValues): ?><div class="empty-state">Sem dados</div><?php endif; ?>
        </div>
    </section>

    <section class="chart-card compact-table">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px"><h3 style="margin:0">Últimos pagamentos</h3><a class="text-link" href="<?= $basePath ?>/pagamentos">Ver todos</a></div>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Aluno</th><th>Plano</th><th>Forma</th><th>Data</th><th>Valor</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach($ultimosPagamentos as $pag): ?>
                    <?php $st=(string)($pag['status']??'pendente'); ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$pag['aluno_nome']) ?></td>
                        <td><?= htmlspecialchars((string)$pag['plano_nome']) ?></td>
                        <td><?= htmlspecialchars(ucwords(str_replace('_',' ',(string)$pag['forma_pagamento']))) ?></td>
                        <td><?= !empty($pag['data_pagamento'])?date('d/m/Y',strtotime((string)$pag['data_pagamento'])):'-' ?></td>
                        <td>R$ <?= number_format((float)$pag['valor'],2,',','.') ?></td>
                        <td><span class="badge badge-<?= $st==='pago'?'success':($st==='pendente'?'warning':'danger') ?>"><?= ucfirst($st) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(!$ultimosPagamentos): ?><tr><td colspan="6">Nenhum pagamento registrado.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
