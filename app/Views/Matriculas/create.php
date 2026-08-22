<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Nova Matrícula</h1>
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

    <form action="<?= $basePath ?>/matriculas/store" method="post">
        <div class="form-group">
            <label for="aluno_id" class="form-label">Aluno</label>
            <select name="aluno_id" id="aluno_id" class="form-control" required>
                <option value="">Selecione um Aluno</option>
                <?php foreach ($alunos as $aluno): ?>
                    <option value="<?= $aluno['id'] ?>" <?= (int)($dados['aluno_id'] ?? 0) === (int)$aluno['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($aluno['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="plano_id" class="form-label">Plano</label>
            <select name="plano_id" id="plano_id" class="form-control" required>
                <option value="">Selecione um Plano</option>
                <?php foreach ($planos as $plano): ?>
                    <option value="<?= $plano['id'] ?>" <?= (int)($dados['plano_id'] ?? 0) === (int)$plano['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($plano['nome']) ?> - R$ <?= number_format((float)$plano['valor'], 2, ',', '.') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?= htmlspecialchars($dados['data_inicio'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="date" name="data_fim" id="data_fim" class="form-control" value="<?= htmlspecialchars($dados['data_fim'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="ativa" <?= ($dados['status'] ?? '') === 'ativa' ? 'selected' : '' ?>>Ativa</option>
                <option value="cancelada" <?= ($dados['status'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                <option value="expirada" <?= ($dados['status'] ?? '') === 'expirada' ? 'selected' : '' ?>>Expirada</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= $basePath ?>/matriculas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
