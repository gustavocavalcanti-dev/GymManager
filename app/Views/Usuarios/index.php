<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Usuários</h1>
            <p class="subtitle">Controle de acessos e perfis</p>
        </div>
        <a href="<?= $basePath ?>/usuarios/create" class="btn btn-primary">+ Novo Usuário</a>
    </div>

    <?php if (empty($usuarios)): ?>
        <div class="empty-state">
            <p>Nenhum usuário cadastrado.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th>Último Login</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td>#<?= $usuario['id'] ?></td>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td><span class="badge badge-<?= $usuario['perfil'] ?>"><?= ucfirst(htmlspecialchars($usuario['perfil'])) ?></span></td>
                            <td>
                                <span class="badge badge-<?= $usuario['ativo'] ? 'success' : 'danger' ?>">
                                    <?= $usuario['ativo'] ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td><?= $usuario['ultimo_login'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_login'])) : 'Nunca' ?></td>
                            <td>
                                <div class="actions">
                                    <a href="<?= $basePath ?>/usuarios/edit?id=<?= $usuario['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                    <a href="<?= $basePath ?>/usuarios/toggle?id=<?= $usuario['id'] ?>" class="btn btn-secondary btn-sm">Alternar Status</a>
                                    <form action="<?= $basePath ?>/usuarios/delete" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza?');">
                                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
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
