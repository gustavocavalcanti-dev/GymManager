<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Editar Matrícula</h1>
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

    <form action="<?= $basePath ?>/matriculas/update" method="post">
        <input type="hidden" name="id" value="<?= $matricula['id'] ?>">
        <div class="form-group">
            <label for="aluno_id" class="form-label">Aluno</label>
            <select name="aluno_id" id="aluno_id" class="form-control" required>
                <?php foreach ($alunos as $aluno): ?>
                    <option value="<?= $aluno['id'] ?>" <?= (int)$matricula['aluno_id'] === (int)$aluno['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($aluno['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="plano_id" class="form-label">Plano</label>
            <select name="plano_id" id="plano_id" class="form-control" required>
                <?php foreach ($planos as $plano): ?>
                    <option value="<?= $plano['id'] ?>" <?= (int)$matricula['plano_id'] === (int)$plano['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($plano['nome']) ?> - R$ <?= number_format((float)$plano['valor'], 2, ',', '.') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?= htmlspecialchars($matricula['data_inicio'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="date" name="data_fim" id="data_fim" class="form-control" value="<?= htmlspecialchars($matricula['data_fim'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="ativa" <?= ($matricula['status'] ?? '') === 'ativa' ? 'selected' : '' ?>>Ativa</option>
                <option value="cancelada" <?= ($matricula['status'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                <option value="expirada" <?= ($matricula['status'] ?? '') === 'expirada' ? 'selected' : '' ?>>Expirada</option>
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
