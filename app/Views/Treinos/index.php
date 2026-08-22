<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Treinos</h1>
            <p class="subtitle">Fichas de treino dos alunos</p>
        </div>
        <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'professor'], true)): ?>
            <a href="<?= $basePath ?>/treinos/create" class="btn btn-primary">+ Novo Treino</a>
        <?php endif; ?>
    </div>

    <?php if (empty($treinos)): ?>
        <div class="empty-state">
            <p>Nenhum treino cadastrado.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Professor</th>
                        <th>Dia da Semana</th>
                        <th>Descrição</th>
                        <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'professor'], true)): ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($treinos as $treino): ?>
                        <tr>
                            <td>#<?= $treino['id'] ?></td>
                            <td><?= htmlspecialchars($treino['aluno_nome']) ?></td>
                            <td><?= htmlspecialchars($treino['professor_nome']) ?></td>
                            <td><?= htmlspecialchars($treino['dia_semana'] ?? '') ?></td>
                            <td><?= htmlspecialchars($treino['descricao']) ?></td>
                            <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'professor'], true)): ?>
                                <td>
                                    <div class="actions">
                                        <a href="<?= $basePath ?>/treinos/edit?id=<?= $treino['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                        <form action="<?= $basePath ?>/treinos/delete" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza?');">
                                            <input type="hidden" name="id" value="<?= $treino['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>
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
