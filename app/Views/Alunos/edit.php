<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>
<div class="page-head"><div><h1 class="page-title">Editar Aluno</h1><p class="subtitle">Atualize os dados cadastrais do aluno.</p></div></div>
<div class="card">
    <?php if (!empty($erros)): ?><div class="alert alert-danger"><ul><?php foreach ($erros as $erro): ?><li><?= htmlspecialchars((string)$erro) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form action="<?= $basePath ?>/alunos/update" method="post">
        <?= Security::campoCSRF() ?><input type="hidden" name="id" value="<?= (int)$aluno['id'] ?>">
        <div class="form-grid">
            <div class="form-group"><label class="form-label" for="nome">Nome completo</label><input class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars((string)($aluno['nome'] ?? '')) ?>"></div>
            <div class="form-group"><label class="form-label" for="cpf">CPF</label><input class="form-control" id="cpf" name="cpf" required maxlength="14" value="<?= htmlspecialchars((string)($aluno['cpf'] ?? '')) ?>"></div>
            <div class="form-group"><label class="form-label" for="email">E-mail</label><input class="form-control" id="email" type="email" name="email" required value="<?= htmlspecialchars((string)($aluno['email'] ?? '')) ?>"></div>
            <div class="form-group"><label class="form-label" for="telefone">Telefone</label><input class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars((string)($aluno['telefone'] ?? '')) ?>"></div>
            <div class="form-group"><label class="form-label" for="data_nascimento">Data de nascimento</label><input class="form-control" id="data_nascimento" type="date" name="data_nascimento" value="<?= htmlspecialchars((string)($aluno['data_nascimento'] ?? '')) ?>"></div>
            <div class="form-group"><label class="form-label" for="status">Status</label><select class="form-control" id="status" name="status"><option value="ativo" <?= ($aluno['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option><option value="inativo" <?= ($aluno['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option></select></div>
            <div class="form-group" style="grid-column:1/-1"><label class="form-label" for="endereco">Endereço</label><input class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars((string)($aluno['endereco'] ?? '')) ?>"></div>
        </div>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Salvar</button><a class="btn btn-secondary" href="<?= $basePath ?>/alunos">Cancelar</a></div>
    </form>
</div>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
