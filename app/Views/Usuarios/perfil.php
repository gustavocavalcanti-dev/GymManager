<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Meu Perfil</h1>
            <p class="subtitle">Gerencie suas informações cadastrais e sua senha</p>
        </div>
    </div>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= $basePath ?>/perfil/update" method="post">
        <?= Security::campoCSRF() ?>
        <div class="form-group">
            <label for="nome" class="form-label">Nome Completo</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label">Perfil de Acesso</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars(['administrador'=>'Administrador','atendente'=>'Recepcionista','professor'=>'Professor'][$usuario['perfil'] ?? ''] ?? ucfirst((string)($usuario['perfil'] ?? ''))) ?>" disabled>
        </div>

        <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 16px;">Alterar Senha</h3>

        <div class="form-group">
            <label for="senha_atual" class="form-label">Senha Atual (obrigatória para alteração)</label>
            <input type="password" name="senha_atual" id="senha_atual" class="form-control">
        </div>
        <div class="form-group">
            <label for="nova_senha" class="form-label">Nova Senha</label>
            <input type="password" name="nova_senha" id="nova_senha" class="form-control" placeholder="Mínimo 6 caracteres">
        </div>
        <div class="form-group">
            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="<?= $basePath ?>/" class="btn btn-secondary">Voltar ao Painel</a>
        </div>
    </form>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
