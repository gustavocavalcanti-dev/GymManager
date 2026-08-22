<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Cadastrar Novo Treino</h1>
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

    <form action="<?= $basePath ?>/treinos/store" method="post">
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
            <label for="professor_id" class="form-label">Professor</label>
            <select name="professor_id" id="professor_id" class="form-control" required>
                <option value="">Selecione um Professor</option>
                <?php foreach ($professores as $prof): ?>
                    <option value="<?= $prof['id'] ?>" <?= (int)($dados['professor_id'] ?? 0) === (int)$prof['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($prof['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="dia_semana" class="form-label">Dia da Semana</label>
            <select name="dia_semana" id="dia_semana" class="form-control" required>
                <option value="">Selecione o Dia</option>
                <option value="Segunda-feira" <?= ($dados['dia_semana'] ?? '') === 'Segunda-feira' ? 'selected' : '' ?>>Segunda-feira</option>
                <option value="Terça-feira" <?= ($dados['dia_semana'] ?? '') === 'Terça-feira' ? 'selected' : '' ?>>Terça-feira</option>
                <option value="Quarta-feira" <?= ($dados['dia_semana'] ?? '') === 'Quarta-feira' ? 'selected' : '' ?>>Quarta-feira</option>
                <option value="Quinta-feira" <?= ($dados['dia_semana'] ?? '') === 'Quinta-feira' ? 'selected' : '' ?>>Quinta-feira</option>
                <option value="Sexta-feira" <?= ($dados['dia_semana'] ?? '') === 'Sexta-feira' ? 'selected' : '' ?>>Sexta-feira</option>
                <option value="Sábado" <?= ($dados['dia_semana'] ?? '') === 'Sábado' ? 'selected' : '' ?>>Sábado</option>
            </select>
        </div>
        <div class="form-group">
            <label for="descricao" class="form-label">Descrição do Treino</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="5" required placeholder="Ex: 3 séries de 10 repetições de supino, agachamento..."><?= htmlspecialchars($dados['descricao'] ?? '') ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= $basePath ?>/treinos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
