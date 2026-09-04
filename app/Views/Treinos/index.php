<?php $basePath = defined('BASE_PATH') ? BASE_PATH : ''; include dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="page-head"><div><h1 class="page-title">Treinos</h1><p class="subtitle">Prescreva e acompanhe os treinos personalizados dos alunos.</p></div></div>
<div data-list data-page-size="8">
    <div class="toolbar"><div class="search-control"><?= UI::icon('search',18) ?><input data-table-search type="search" placeholder="Pesquisar treino por aluno..."></div><?php if ($usuarioLogado && in_array($usuarioLogado['perfil'],['admin','professor'],true)): ?><a href="<?= $basePath ?>/treinos/create" class="btn btn-primary"><?= UI::icon('plus',18) ?> Novo Treino</a><?php endif; ?></div>
    <div class="table-card"><div class="table-responsive"><table class="table"><thead><tr><th>Código</th><th>Aluno</th><th>Professor</th><th>Objetivo</th><th>Exercícios</th><th>Criado em</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($treinos as $treino):
            $objetivo = (string)($treino['objetivo'] ?? ($treino['dia_semana'] ?? 'Treino'));
            $ex = (int)($treino['exercicios'] ?? max(1,count(array_filter(preg_split('/[\r\n,;]+/',(string)($treino['descricao'] ?? '')) ?: []))));
        ?>
            <tr data-edit-url="<?= $basePath ?>/treinos/edit?id=<?= (int)$treino['id'] ?>"><td>TR<?= str_pad((string)(int)$treino['id'],4,'0',STR_PAD_LEFT) ?></td><td><strong><?= htmlspecialchars((string)$treino['aluno_nome']) ?></strong></td><td><?= htmlspecialchars((string)$treino['professor_nome']) ?></td><td><span class="badge badge-outline"><?= htmlspecialchars($objetivo) ?></span></td><td><?= $ex ?> exercício<?= $ex === 1 ? '' : 's' ?></td><td><?= !empty($treino['criado_em']) ? date('d/m/Y',strtotime((string)$treino['criado_em'])) : '-' ?></td><td><?php if ($usuarioLogado && in_array($usuarioLogado['perfil'],['admin','professor'],true)): ?><form action="<?= $basePath ?>/treinos/delete" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este treino?')"><input type="hidden" name="id" value="<?= (int)$treino['id'] ?>"><button class="text-danger">Excluir</button></form><?php endif; ?></td></tr>
        <?php endforeach; ?>
        <?php if (!$treinos): ?><tr><td colspan="7"><div class="empty-state">Nenhum treino cadastrado.</div></td></tr><?php endif; ?>
    </tbody></table></div><div class="table-footer"><span data-table-count></span><div class="pagination"><button type="button" data-prev>‹ Previous</button><button type="button" class="current" data-page-current>1</button><button type="button" data-next>Next ›</button></div></div></div>
</div>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
