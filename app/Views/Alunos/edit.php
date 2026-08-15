<?php

declare(strict_types=1);

$basePath = defined('BASE_PATH') ? BASE_PATH : '';
$aluno = $aluno ?? [];
$erros = $erros ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno - GymManager</title>
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

        <?php if (!empty($erros)): ?>
            <div class="alert alert-danger">
                <div>
                    <strong>✕ Foram encontrados os seguintes erros no formulário:</strong>
                    <ul>
                        <?php foreach ($erros as $erro): ?>
                            <li><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($aluno) || !isset($aluno['id'])): ?>
            <main class="card">
                <div class="empty-state">
                    <p>Aluno não localizado ou dados indisponíveis.</p>
                    <a href="<?= $basePath ?>/alunos" class="btn btn-secondary">
                        Voltar para a Lista de Alunos
                    </a>
                </div>
            </main>
        <?php else: ?>
            <main class="card">
                <div class="card-header">
                    <div>
                        <h1 class="page-title">Editar Aluno #<?= (int)$aluno['id'] ?></h1>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">
                            Atualize as informações cadastrais do aluno.
                        </p>
                    </div>
                    <a href="<?= $basePath ?>/alunos" class="btn btn-secondary">
                        ← Voltar para Lista
                    </a>
                </div>

                <form action="<?= $basePath ?>/alunos/update?id=<?= (int)$aluno['id'] ?>" method="post" novalidate>
                    <input type="hidden" name="id" value="<?= (int)$aluno['id'] ?>">

                    <div class="form-group">
                        <label for="nome" class="form-label">
                            Nome Completo <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="nome" 
                               id="nome" 
                               class="form-control" 
                               value="<?= htmlspecialchars((string)($aluno['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" 
                               required 
                               minlength="3" 
                               maxlength="100">
                        <div class="form-help">Informe o nome completo do aluno (mínimo de 3 caracteres).</div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            E-mail <span class="required">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control" 
                               value="<?= htmlspecialchars((string)($aluno['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" 
                               required 
                               maxlength="150">
                        <div class="form-help">Informe um endereço de e-mail válido para contato.</div>
                    </div>

                    <div class="form-group">
                        <label for="telefone" class="form-label">
                            Telefone / WhatsApp
                        </label>
                        <input type="text" 
                               name="telefone" 
                               id="telefone" 
                               class="form-control" 
                               value="<?= htmlspecialchars((string)($aluno['telefone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" 
                               maxlength="20">
                        <div class="form-help">Opcional. Informe o telefone com DDD.</div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            Atualizar Aluno
                        </button>
                        <a href="<?= $basePath ?>/alunos" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </main>
        <?php endif; ?>
    </div>
</body>
</html>
