<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Planos</h1>
            <p class="subtitle">Configure os planos oferecidos pela academia.</p>
        </div>
        <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
            <a href="<?= $basePath ?>/planos/create" class="btn btn-primary">+ Novo Plano</a>
        <?php endif; ?>
    </div>

    <?php if (empty($planos)): ?>
        <div class="empty-state">
            <p>Nenhum plano cadastrado.</p>
        </div>
    <?php else: ?>
        <div class="plans-grid">
            <?php foreach ($planos as $plano): ?>
                <?php 
                $isPopular = stripos($plano['nome'], 'Premium') !== false;
                ?>
                <div class="plan-card <?= $isPopular ? 'popular' : '' ?>">
                    <?php if ($isPopular): ?>
                        <div class="plan-popular-badge">POPULAR</div>
                    <?php endif; ?>
                    
                    <div class="plan-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3><?= htmlspecialchars($plano['nome']) ?></h3>
                            <span class="badge badge-success">Ativo</span>
                        </div>
                        <p class="plan-price">
                            R$ <?= number_format((float)$plano['valor'], 2, ',', '.') ?> 
                            <span>/ <?= $plano['duracao_meses'] ?> <?= $plano['duracao_meses'] == 1 ? 'mês' : 'meses' ?></span>
                        </p>
                    </div>

                    <ul class="plan-features">
                        <li>Acesso livre à musculação</li>
                        <li>Área de cardio inclusa</li>
                        <?php if ($plano['duracao_meses'] >= 3): ?>
                            <li>Avaliação física inclusa</li>
                        <?php endif; ?>
                        <?php if ($isPopular): ?>
                            <li>Aulas coletivas limitadas</li>
                            <li>Personal trainer 1x/semana</li>
                        <?php endif; ?>
                    </ul>

                    <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--border-color);">
                            <a href="<?= $basePath ?>/planos/edit?id=<?= $plano['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                            <form action="<?= $basePath ?>/planos/delete" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este plano?');">
                                <input type="hidden" name="id" value="<?= $plano['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
