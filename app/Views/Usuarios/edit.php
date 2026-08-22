<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Editar Usuário</h1>
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

    <form action="<?= $basePath ?>/usuarios/update" method="post">
        <?= Security::campoCSRF() ?>
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <div class="form-group">
            <label for="nome" class="form-label">Nome Completo</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="senha" class="form-label">Senha (deixe em branco para manter a mesma)</label>
            <input type="password" name="senha" id="senha" class="form-control" placeholder="Mínimo 6 caracteres">
        </div>
        <div class="form-group">
            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control">
        </div>
        <div class="form-group">
            <label for="perfil" class="form-label">Perfil de Acesso</label>
            <select name="perfil" id="perfil" class="form-control">
                <option value="recepcionista" <?= ($usuario['perfil'] ?? '') === 'recepcionista' ? 'selected' : '' ?>>Recepcionista</option>
                <option value="professor" <?= ($usuario['perfil'] ?? '') === 'professor' ? 'selected' : '' ?>>Professor</option>
                <option value="admin" <?= ($usuario['perfil'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= $basePath ?>/usuarios" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
