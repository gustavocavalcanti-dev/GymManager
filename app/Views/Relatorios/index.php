<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Relatórios Gerenciais</h1>
            <p class="subtitle">Estatísticas gerais de uso e faturamento</p>
        </div>
    </div>

    <div class="dashboard-grid" style="margin-top: 16px;">
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <h3>Total de Alunos</h3>
            <p class="stat-number"><?= $totalAlunos ?></p>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <h3>Total de Professores</h3>
            <p class="stat-number"><?= $totalProfessores ?></p>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <h3>Total de Planos</h3>
            <p class="stat-number"><?= $totalPlanos ?></p>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <h3>Matrículas Totais</h3>
            <p class="stat-number"><?= $totalMatriculas ?></p>
        </div>
        <div class="stat-card" style="background-color: var(--success-bg); border-left: 4px solid var(--success);">
            <h3>Total Faturado</h3>
            <p class="stat-number" style="color: var(--success);">R$ <?= number_format($totalFaturamento, 2, ',', '.') ?></p>
        </div>
    </div>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
