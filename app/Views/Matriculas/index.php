<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Matrículas</h1>
            <p class="subtitle">Controle de matrículas e planos de alunos</p>
        </div>
        <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
            <a href="<?= $basePath ?>/matriculas/create" class="btn btn-primary">+ Nova Matrícula</a>
        <?php endif; ?>
    </div>

    <?php if (empty($matriculas)): ?>
        <div class="empty-state">
            <p>Nenhuma matrícula registrada.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Plano</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Status</th>
                        <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriculas as $mat): ?>
                        <tr>
                            <td>#<?= $mat['id'] ?></td>
                            <td><?= htmlspecialchars($mat['aluno_nome']) ?></td>
                            <td><?= htmlspecialchars($mat['plano_nome']) ?></td>
                            <td><?= date('d/m/Y', strtotime($mat['data_inicio'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($mat['data_fim'])) ?></td>
                            <td>
                                <span class="badge badge-<?= $mat['status'] === 'ativa' ? 'success' : ($mat['status'] === 'cancelada' ? 'danger' : 'secondary') ?>">
                                    <?= ucfirst(htmlspecialchars($mat['status'])) ?>
                                </span>
                            </td>
                            <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
                                <td>
                                    <div class="actions">
                                        <a href="<?= $basePath ?>/matriculas/edit?id=<?= $mat['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                        <form action="<?= $basePath ?>/matriculas/delete" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza?');">
                                            <input type="hidden" name="id" value="<?= $mat['id'] ?>">
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
