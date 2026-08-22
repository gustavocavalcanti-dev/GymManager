<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Painel de Controle</h1>
            <p class="subtitle">Visão geral do sistema de gestão da academia</p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card">
            <h3>Alunos</h3>
            <p class="stat-number"><?= $totalAlunos ?></p>
            <p class="stat-desc">Cadastros totais</p>
        </div>
        <div class="stat-card">
            <h3>Professores</h3>
            <p class="stat-number"><?= $totalProfessores ?></p>
            <p class="stat-desc">Profissionais cadastrados</p>
        </div>
        <div class="stat-card">
            <h3>Matrículas Ativas</h3>
            <p class="stat-number"><?= $matriculasAtivas ?> / <?= $totalMatriculas ?></p>
            <p class="stat-desc">Matrículas ativas atualmente</p>
        </div>
        <div class="stat-card">
            <h3>Treinos</h3>
            <p class="stat-number"><?= $totalTreinos ?></p>
            <p class="stat-desc">Treinos prescritos</p>
        </div>
        <div class="stat-card">
            <h3>Planos</h3>
            <p class="stat-number"><?= $totalPlanos ?></p>
            <p class="stat-desc">Tipos de planos</p>
        </div>
        <div class="stat-card">
            <h3>Faturamento</h3>
            <p class="stat-number">R$ <?= number_format($totalPagamentos, 2, ',', '.') ?></p>
            <p class="stat-desc">Total pago no sistema</p>
        </div>
    </div>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
