<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Pagamentos</h1>
            <p class="subtitle">Histórico e registro de mensalidades pagas</p>
        </div>
        <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
            <a href="<?= $basePath ?>/pagamentos/create" class="btn btn-primary">+ Registrar Pagamento</a>
        <?php endif; ?>
    </div>

    <?php if (empty($pagamentos)): ?>
        <div class="empty-state">
            <p>Nenhum pagamento registrado.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Plano</th>
                        <th>Valor Pago</th>
                        <th>Data do Pagamento</th>
                        <th>Forma de Pagamento</th>
                        <th>Status</th>
                        <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagamentos as $pag): ?>
                        <tr>
                            <td>#<?= $pag['id'] ?></td>
                            <td><?= htmlspecialchars($pag['aluno_nome']) ?></td>
                            <td><?= htmlspecialchars($pag['plano_nome']) ?></td>
                            <td>R$ <?= number_format((float)$pag['valor'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($pag['data_pagamento'])) ?></td>
                            <td><?= ucfirst(htmlspecialchars($pag['forma_pagamento'])) ?></td>
                            <td>
                                <span class="badge badge-<?= $pag['status'] === 'pago' ? 'success' : ($pag['status'] === 'pendente' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst(htmlspecialchars($pag['status'])) ?>
                                </span>
                            </td>
                            <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
                                <td>
                                    <div class="actions">
                                        <a href="<?= $basePath ?>/pagamentos/edit?id=<?= $pag['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                        <form action="<?= $basePath ?>/pagamentos/delete" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza?');">
                                            <input type="hidden" name="id" value="<?= $pag['id'] ?>">
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
