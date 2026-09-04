<?php $basePath = defined('BASE_PATH') ? BASE_PATH : ''; include dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="page-head"><div><h1 class="page-title">Professores</h1><p class="subtitle">Cadastro e gerenciamento da equipe de professores.</p></div></div>
<div data-list data-page-size="8">
    <div class="toolbar">
        <div class="search-control"><?= UI::icon('search',18) ?><input data-table-search type="search" placeholder="Pesquisar professor..."></div>
        <div class="filter-select"><?= UI::icon('filter',16) ?><select data-table-filter data-filter-attr="data-status"><option value="todos">Todos</option><option value="ativo">Ativo</option><option value="inativo">Inativo</option></select></div>
        <?php if ($usuarioLogado && $usuarioLogado['perfil'] === 'admin'): ?><a href="<?= $basePath ?>/professores/create" class="btn btn-primary"><?= UI::icon('plus',18) ?> Novo Professor</a><?php endif; ?>
    </div>
    <div class="table-card"><div class="table-responsive"><table class="table"><thead><tr><th>Professor</th><th>Especialidade</th><th>Telefone</th><th>Status</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($professores as $prof): $status = strtolower((string)($prof['status'] ?? 'ativo')); ?>
            <tr data-status="<?= htmlspecialchars($status) ?>" data-edit-url="<?= $basePath ?>/professores/edit?id=<?= (int)$prof['id'] ?>">
                <td><div class="person-cell"><span class="avatar"><?= htmlspecialchars(UI::initials((string)$prof['nome'])) ?></span><div class="person-copy"><strong><?= htmlspecialchars((string)$prof['nome']) ?></strong><span><?= htmlspecialchars((string)$prof['email']) ?></span></div></div></td>
                <td><?= htmlspecialchars((string)($prof['especialidade'] ?? '—')) ?></td><td><?= htmlspecialchars((string)($prof['telefone'] ?? '—')) ?></td>
                <td><span class="badge badge-<?= $status === 'ativo' ? 'success' : 'secondary' ?>"><?= ucfirst($status) ?></span></td>
                <td><?php if ($usuarioLogado && $usuarioLogado['perfil'] === 'admin'): ?><form action="<?= $basePath ?>/professores/delete" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este professor?')"><input type="hidden" name="id" value="<?= (int)$prof['id'] ?>"><button class="text-danger">Excluir</button></form><?php else: ?><span class="subtitle">Visualização</span><?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$professores): ?><tr><td colspan="5"><div class="empty-state">Nenhum professor cadastrado.</div></td></tr><?php endif; ?>
    </tbody></table></div><div class="table-footer"><span data-table-count></span><div class="pagination"><button type="button" data-prev>‹ Previous</button><button type="button" class="current" data-page-current>1</button><button type="button" data-next>Next ›</button></div></div></div>
</div>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
