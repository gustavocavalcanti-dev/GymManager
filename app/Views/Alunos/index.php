<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Alunos</h1>
            <p class="subtitle">Gerenciamento de alunos da academia</p>
        </div>
        <a href="<?= $basePath ?>/alunos/create" class="btn btn-primary">+ Novo Aluno</a>
    </div>

    <?php if (empty($alunos)): ?>
        <div class="empty-state">
            <p>Nenhum aluno cadastrado.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td>#<?= $aluno['id'] ?></td>
                            <td><?= htmlspecialchars($aluno['nome']) ?></td>
                            <td><?= htmlspecialchars($aluno['email']) ?></td>
                            <td><?= htmlspecialchars($aluno['telefone'] ?? 'Não informado') ?></td>
                            <td>
                                <div class="actions">
                                    <a href="<?= $basePath ?>/alunos/edit?id=<?= $aluno['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                    <form action="<?= $basePath ?>/alunos/delete" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza?');">
                                        <input type="hidden" name="id" value="<?= $aluno['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
