<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Professores</h1>
            <p class="subtitle">Gerenciamento de professores da academia</p>
        </div>
        <?php if ($usuarioLogado && $usuarioLogado['perfil'] === 'admin'): ?>
            <a href="<?= $basePath ?>/professores/create" class="btn btn-primary">+ Novo Professor</a>
        <?php endif; ?>
    </div>

    <?php if (empty($professores)): ?>
        <div class="empty-state">
            <p>Nenhum professor cadastrado.</p>
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
                        <th>Especialidade</th>
                        <?php if ($usuarioLogado && $usuarioLogado['perfil'] === 'admin'): ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($professores as $prof): ?>
                        <tr>
                            <td>#<?= $prof['id'] ?></td>
                            <td><?= htmlspecialchars($prof['nome']) ?></td>
                            <td><?= htmlspecialchars($prof['email']) ?></td>
                            <td><?= htmlspecialchars($prof['telefone'] ?? 'Não informado') ?></td>
                            <td><?= htmlspecialchars($prof['especialidade'] ?? 'Não informada') ?></td>
                            <?php if ($usuarioLogado && $usuarioLogado['perfil'] === 'admin'): ?>
                                <td>
                                    <div class="actions">
                                        <a href="<?= $basePath ?>/professores/edit?id=<?= $prof['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                        <form action="<?= $basePath ?>/professores/delete" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza?');">
                                            <input type="hidden" name="id" value="<?= $prof['id'] ?>">
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
