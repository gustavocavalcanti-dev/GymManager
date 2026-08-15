<?php

declare(strict_types=1);

$basePath = defined('BASE_PATH') ? BASE_PATH : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Alunos - GymManager</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header-nav">
            <div class="app-title">GymManager</div>
            <nav class="nav-links">
                <a href="<?= $basePath ?>/">Dashboard</a>
                <a href="<?= $basePath ?>/alunos" style="color: var(--primary); font-weight: 700;">Alunos</a>
                <a href="<?= $basePath ?>/professores">Professores</a>
                <a href="<?= $basePath ?>/planos">Planos</a>
                <a href="<?= $basePath ?>/treinos">Treinos</a>
            </nav>
        </header>

        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success">
                <strong>✓ Sucesso:</strong> <?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger">
                <strong>✕ Erro:</strong> <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <main class="card">
            <div class="card-header">
                <div>
                    <h1 class="page-title">Alunos Cadastrados</h1>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">
                        Gerencie as informações dos alunos da academia.
                    </p>
                </div>
                <a href="<?= $basePath ?>/alunos/create" class="btn btn-primary">
                    + Cadastrar Novo Aluno
                </a>
            </div>

            <?php if (empty($alunos)): ?>
                <div class="empty-state">
                    <p>Nenhum aluno cadastrado no momento.</p>
                    <a href="<?= $basePath ?>/alunos/create" class="btn btn-primary">
                        Cadastrar Primeiro Aluno
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th style="width: 170px; text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $aluno): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars((string)$aluno['id'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><?= htmlspecialchars($aluno['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($aluno['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($aluno['telefone'] ?: 'Não informado', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <div class="actions" style="justify-content: center;">
                                            <a href="<?= $basePath ?>/alunos/edit?id=<?= (int)$aluno['id'] ?>" class="btn btn-secondary btn-sm">
                                                Editar
                                            </a>
                                            <a href="<?= $basePath ?>/alunos/delete?id=<?= (int)$aluno['id'] ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Tem certeza de que deseja excluir o aluno &quot;<?= htmlspecialchars(addslashes($aluno['nome']), ENT_QUOTES, 'UTF-8') ?>&quot;?');">
                                                Excluir
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
