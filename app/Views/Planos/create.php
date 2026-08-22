<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Cadastrar Novo Plano</h1>
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

    <form action="<?= $basePath ?>/planos/store" method="post">
        <div class="form-group">
            <label for="nome" class="form-label">Nome do Plano</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($dados['nome'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="valor" class="form-label">Valor (R$)</label>
            <input type="number" step="0.01" name="valor" id="valor" class="form-control" value="<?= htmlspecialchars((string)($dados['valor'] ?? '')) ?>" required>
        </div>
        <div class="form-group">
            <label for="duracao_meses" class="form-label">Duração (Meses)</label>
            <input type="number" name="duracao_meses" id="duracao_meses" class="form-control" value="<?= htmlspecialchars((string)($dados['duracao_meses'] ?? 1)) ?>" required>
        </div>
        <div class="form-group">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="3"><?= htmlspecialchars($dados['descricao'] ?? '') ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= $basePath ?>/planos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
