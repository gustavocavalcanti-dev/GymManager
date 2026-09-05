<?php

$basePath = defined('BASE_PATH') ? BASE_PATH : '';

include dirname(__DIR__) . '/layouts/header.php';

$receitaMensal = $receitaMensal ?? [];
$novasMatriculas = $novasMatriculas ?? [];

$tipo = (string)($_GET['tipo'] ?? 'receita');

$mesesAbrev = [
    1 => 'Jan',
    2 => 'Fev',
    3 => 'Mar',
    4 => 'Abr',
    5 => 'Mai',
    6 => 'Jun',
    7 => 'Jul',
    8 => 'Ago',
    9 => 'Set',
    10 => 'Out',
    11 => 'Nov',
    12 => 'Dez'
];


/*
|--------------------------------------------------------------------------
| PREENCHE TODOS OS MESES DO PERÍODO
|--------------------------------------------------------------------------
*/

$receitaPorChave = [];

foreach ($receitaMensal as $row) {

    $chave = (string)($row['chave'] ?? '');

    if ($chave !== '') {
        $receitaPorChave[$chave] =
            (float)($row['total'] ?? 0);
    }
}


$matriculasPorChave = [];

foreach ($novasMatriculas as $row) {

    $chave = (string)($row['chave'] ?? '');

    if ($chave !== '') {
        $matriculasPorChave[$chave] =
            (int)($row['total'] ?? 0);
    }
}


$dataInicio = new DateTime(
    date(
        'Y-m-01',
        strtotime((string)$de)
    )
);

$dataFim = new DateTime(
    date(
        'Y-m-01',
        strtotime((string)$ate)
    )
);

$dataFim->modify('+1 month');


$periodo = new DatePeriod(
    $dataInicio,
    new DateInterval('P1M'),
    $dataFim
);


$months = [];
$receitas = [];

$matMonths = [];
$matValues = [];


foreach ($periodo as $data) {

    $chave = $data->format('Y-m');

    $numeroMes = (int)$data->format('n');

    $label = $mesesAbrev[$numeroMes];

    /*
     * Se o relatório atravessar anos,
     * mostra também o ano.
     */
    if (
        $dataInicio->format('Y')
        !==
        $dataFim->modify('-1 month')->format('Y')
    ) {
        $label .= '/' . $data->format('y');

        $dataFim->modify('+1 month');
    }


    $months[] = $label;

    $receitas[] =
        (float)($receitaPorChave[$chave] ?? 0);


    $matMonths[] = $label;

    $matValues[] =
        (int)($matriculasPorChave[$chave] ?? 0);
}


/*
|--------------------------------------------------------------------------
| GRÁFICO DE RECEITA
|--------------------------------------------------------------------------
*/

function gmReportLine(
    array $values,
    array $labels
): string {

    $w = 620;
    $h = 235;

    $l = 90;
    $r = 14;
    $t = 18;
    $b = 30;

    $pw = $w - $l - $r;
    $ph = $h - $t - $b;


    $maiorValor = max(
        0,
        ...array_map(
            'floatval',
            $values ?: [0]
        )
    );


    $max = $maiorValor > 0
        ? $maiorValor
        : 1;


    $n = max(
        1,
        count($values)
    );


    $pts = [];


    foreach ($values as $i => $v) {

        $x = $l + (
            $n === 1
                ? $pw / 2
                : $pw * $i / ($n - 1)
        );


        $y = $t
            + $ph
            - (
                (float)$v
                / $max
                * $ph
                * .9
            );


        $pts[] = [
            round($x, 1),
            round($y, 1)
        ];
    }


    $poly = implode(
        ' ',
        array_map(
            fn($p) =>
                $p[0] . ',' . $p[1],
            $pts
        )
    );


    ob_start();

    ?>

    <svg
        class="chart-svg"
        viewBox="0 0 <?= $w ?> <?= $h ?>"
        preserveAspectRatio="none"
        aria-hidden="true"
    >

        <?php
        for ($i = 0; $i <= 4; $i++):

            $y = $t + $ph * $i / 4;

            $v = $maiorValor > 0
                ? $max * (1 - $i / 4)
                : 0;
        ?>

            <line
                class="chart-grid-line"
                x1="<?= $l ?>"
                y1="<?= $y ?>"
                x2="<?= $l + $pw ?>"
                y2="<?= $y ?>"
            />


            <text
                class="chart-axis-label"
                x="0"
                y="<?= $y + 4 ?>"
            >
                R$ <?= number_format(
                    $v,
                    2,
                    ',',
                    '.'
                ) ?>
            </text>

        <?php endfor; ?>


        <?php if ($pts): ?>

            <polyline
                class="chart-line"
                points="<?= $poly ?>"
            />

        <?php endif; ?>


        <?php foreach ($pts as $i => $p): ?>

            <circle
                cx="<?= $p[0] ?>"
                cy="<?= $p[1] ?>"
                r="3"
                fill="#fff"
                stroke="#2164e9"
                stroke-width="2"
            />


            <text
                class="chart-axis-label"
                x="<?= $p[0] - 8 ?>"
                y="<?= $h - 7 ?>"
            >
                <?= htmlspecialchars(
                    (string)(
                        $labels[$i] ?? ''
                    )
                ) ?>
            </text>

        <?php endforeach; ?>

    </svg>

    <?php

    return (string)ob_get_clean();
}


/*
|--------------------------------------------------------------------------
| MANTÉM O PERÍODO NOS BOTÕES DE EXPORTAÇÃO
|--------------------------------------------------------------------------
*/

$queryExportacao = http_build_query([
    'tipo' => $tipo,
    'de' => $de,
    'ate' => $ate
]);

?>


<div class="page-head">

    <div>

        <h1 class="page-title">
            Relatórios
        </h1>

        <p class="subtitle">
            Analise a performance da academia
            em diferentes períodos.
        </p>

    </div>


    <div class="page-actions">

        <a
            class="btn btn-secondary"
            href="<?= $basePath ?>/relatorios/imprimir?<?= htmlspecialchars($queryExportacao) ?>"
            target="_blank"
        >

            <?= UI::icon('clipboard', 17) ?>

            Exportar PDF

        </a>


        <a
            class="btn btn-secondary"
            href="<?= $basePath ?>/relatorios/exportar-excel?<?= htmlspecialchars($queryExportacao) ?>"
        >

            <?= UI::icon('database', 17) ?>

            Exportar Excel

        </a>

    </div>

</div>


<form
    method="get"
    action="<?= $basePath ?>/relatorios"
    class="panel filter-panel"
>

    <div class="filter-panel-title">

        <?= UI::icon('filter', 18) ?>

        Filtros

    </div>


    <div class="report-filter-grid">


        <div>

            <label class="form-label">
                Tipo
            </label>


            <select
                class="form-control"
                name="tipo"
            >

                <option
                    value="receita"
                    <?= $tipo === 'receita'
                        ? 'selected'
                        : '' ?>
                >
                    Receita
                </option>


                <option
                    value="matriculas"
                    <?= $tipo === 'matriculas'
                        ? 'selected'
                        : '' ?>
                >
                    Matrículas
                </option>

            </select>

        </div>


        <div>

            <label class="form-label">
                De
            </label>

            <input
                class="form-control"
                type="date"
                name="de"
                value="<?= htmlspecialchars(
                    (string)(
                        $de
                        ?? date('Y-01-01')
                    )
                ) ?>"
            >

        </div>


        <div>

            <label class="form-label">
                Até
            </label>

            <input
                class="form-control"
                type="date"
                name="ate"
                value="<?= htmlspecialchars(
                    (string)(
                        $ate
                        ?? date('Y-m-d')
                    )
                ) ?>"
            >

        </div>


        <button
            class="btn btn-primary"
            type="submit"
        >
            Aplicar filtros
        </button>

    </div>

</form>


<div class="report-stats">

    <div class="panel report-stat">

        <div class="stat-label">
            Receita total no período
        </div>

        <div class="stat-value">

            R$
            <?= number_format(
                (float)$totalFaturamento,
                2,
                ',',
                '.'
            ) ?>

        </div>

    </div>


    <div class="panel report-stat">

        <div class="stat-label">
            Alunos ativos
        </div>

        <div class="stat-value">

            <?= (int)(
                $alunosAtivos
                ?? $totalAlunos
            ) ?>

        </div>

    </div>


    <div class="panel report-stat">

        <div class="stat-label">
            Inadimplentes
        </div>

        <div class="stat-value">

            <?= (int)(
                $inadimplentes
                ?? 0
            ) ?>

        </div>

    </div>


    <div class="panel report-stat">

        <div class="stat-label">
            Ticket médio
        </div>

        <div class="stat-value">

            R$
            <?= number_format(
                (float)(
                    $ticketMedio
                    ?? 0
                ),
                2,
                ',',
                '.'
            ) ?>

        </div>

    </div>

</div>


<div class="report-charts">

    <section class="chart-card">

        <h3>
            Receita por mês
        </h3>


        <?= gmReportLine(
            $receitas,
            $months
        ) ?>

    </section>


    <section class="chart-card">

        <h3>
            Novas matrículas por mês
        </h3>


        <?php

        $maxMat = max(
            1,
            ...($matValues ?: [1])
        );

        ?>


        <div class="bar-chart">

            <?php
            foreach (
                $matValues
                as $i => $v
            ):
            ?>

                <?php

                $altura = $v > 0
                    ? max(
                        4,
                        $v / $maxMat * 82
                    )
                    : 0;

                ?>


                <div class="bar-group">


                    <strong
                        class="bar-value"
                        style="
                            font-size:12px;
                            margin-bottom:6px;
                        "
                    >

                        <?= (int)$v ?>

                    </strong>


                    <div
                        class="bar green"
                        style="
                            height:
                            <?= $altura ?>%
                        "
                    >
                    </div>


                    <span class="bar-label">

                        <?= htmlspecialchars(
                            (string)(
                                $matMonths[$i]
                                ?? ''
                            )
                        ) ?>

                    </span>

                </div>

            <?php endforeach; ?>


            <?php if (!$matValues): ?>

                <div class="empty-state">
                    Sem dados
                </div>

            <?php endif; ?>

        </div>

    </section>

</div>


<?php

include dirname(__DIR__)
    . '/layouts/footer.php';

?>