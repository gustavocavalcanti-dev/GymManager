<?php

declare(strict_types=1);

$basePath = defined('BASE_PATH') ? BASE_PATH : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GymManager</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header-nav">
            <div class="app-title">GymManager</div>
            <nav class="nav-links">
                <a href="<?= $basePath ?>/" style="color: var(--primary); font-weight: 700;">Dashboard</a>
                <a href="<?= $basePath ?>/alunos">Alunos</a>
                <a href="<?= $basePath ?>/professores">Professores</a>
                <a href="<?= $basePath ?>/planos">Planos</a>
                <a href="<?= $basePath ?>/treinos">Treinos</a>
            </nav>
        </header>

        <main class="card">
            <div class="card-header">
                <div>
                    <h1 class="page-title">Painel Principal</h1>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">
                        Bem-vindo ao sistema de gestão de academia GymManager.
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 12px;">
                <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; background: #fafafa;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 8px; color: var(--primary);">Módulo de Alunos</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">
                        Cadastro, edição, consulta e exclusão de alunos.
                    </p>
                    <a href="<?= $basePath ?>/alunos" class="btn btn-primary btn-sm">
                        Gerenciar Alunos &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
