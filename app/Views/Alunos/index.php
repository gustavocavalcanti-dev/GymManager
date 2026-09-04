<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>
<div class="page-head">
    <div>
        <h1 class="page-title">Alunos</h1>
        <p class="subtitle">Gerencie o cadastro completo dos alunos da academia.</p>
    </div>
</div>

<div data-list data-page-size="8">
    <div class="toolbar">
        <div class="search-control">
            <?= UI::icon('search', 18) ?>
            <input data-table-search type="search" value="<?= htmlspecialchars((string)($_GET['busca'] ?? '')) ?>" placeholder="Pesquisar por nome, CPF ou e-mail...">
        </div>
        <div class="filter-select">
            <?= UI::icon('filter', 16) ?>
            <select data-table-filter data-filter-attr="data-status">
                <option value="todos">Todos</option><option value="ativo">Ativo</option><option value="pendente">Pendente</option><option value="inativo">Inativo</option>
            </select>
        </div>
        <div class="filter-select">
            <?= UI::icon('filter', 16) ?>
            <select data-table-filter data-filter-attr="data-plano">
                <option value="todos">Todos</option>
                <?php foreach (array_values(array_unique(array_filter(array_column($alunos, 'plano_nome')))) as $p): ?>
                    <option value="<?= htmlspecialchars(strtolower((string)$p)) ?>"><?= htmlspecialchars((string)$p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <a href="<?= $basePath ?>/alunos/create" class="btn btn-primary"><?= UI::icon('plus', 18) ?> Novo Aluno</a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Aluno</th><th>CPF</th><th>Telefone</th><th>Plano</th><th>Status</th><th>Ações</th></tr></thead>
                <tbody>
                <?php foreach ($alunos as $aluno):
                    $status = strtolower((string)($aluno['status'] ?? 'ativo'));
                    $plano = (string)($aluno['plano_nome'] ?? '—');
                ?>
                    <tr data-status="<?= htmlspecialchars($status) ?>" data-plano="<?= htmlspecialchars(strtolower($plano)) ?>" data-edit-url="<?= $basePath ?>/alunos/edit?id=<?= (int)$aluno['id'] ?>">
                        <td><div class="person-cell"><span class="avatar"><?= htmlspecialchars(UI::initials((string)$aluno['nome'])) ?></span><div class="person-copy"><strong><?= htmlspecialchars((string)$aluno['nome']) ?></strong><span><?= htmlspecialchars((string)$aluno['email']) ?></span></div></div></td>
                        <td><?= htmlspecialchars((string)($aluno['cpf'] ?? '—')) ?></td>
                        <td><?= htmlspecialchars((string)($aluno['telefone'] ?? '—')) ?></td>
                        <td><?= htmlspecialchars($plano) ?></td>
                        <td><span class="badge badge-<?= $status === 'ativo' ? 'success' : ($status === 'pendente' ? 'warning' : 'secondary') ?>"><?= ucfirst($status) ?></span></td>
                        <td><form action="<?= $basePath ?>/alunos/delete" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este aluno?')"><input type="hidden" name="id" value="<?= (int)$aluno['id'] ?>"><button class="text-danger" type="submit">Excluir</button></form></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$alunos): ?><tr><td colspan="6"><div class="empty-state">Nenhum aluno cadastrado.</div></td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-footer"><span data-table-count></span><div class="pagination"><button type="button" data-prev>‹ Previous</button><button type="button" class="current" data-page-current>1</button><button type="button" data-next>Next ›</button></div></div>
    </div>
</div>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
