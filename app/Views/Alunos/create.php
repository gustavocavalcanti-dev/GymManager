<?php

declare(strict_types=1);

$basePath = defined('BASE_PATH') ? BASE_PATH : '';
$dados = $dados ?? ['nome' => '', 'email' => '', 'telefone' => ''];
$erros = $erros ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Aluno - GymManager</title>
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

        <main class="card">
            <div class="card-header">
                <div>
                    <h1 class="page-title">Cadastrar Novo Aluno</h1>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">
                        Preencha os campos abaixo para registrar o aluno no sistema.
                    </p>
                </div>
                <a href="<?= $basePath ?>/alunos" class="btn btn-secondary">
                    ← Voltar para Lista
                </a>
            </div>

            <form action="<?= $basePath ?>/alunos/store" method="post" novalidate>
                <div class="form-group">
                    <label for="nome" class="form-label">
                        Nome Completo <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="nome" 
                           id="nome" 
                           class="form-control" 
                           value="<?= htmlspecialchars($dados['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                           placeholder="Ex: João da Silva" 
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
                           value="<?= htmlspecialchars($dados['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                           placeholder="Ex: joao.silva@email.com" 
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
                           value="<?= htmlspecialchars($dados['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                           placeholder="Ex: (11) 98765-4321" 
                           maxlength="20">
                    <div class="form-help">Opcional. Informe o telefone com DDD.</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Salvar Aluno
                    </button>
                    <a href="<?= $basePath ?>/alunos" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
