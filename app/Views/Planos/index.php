<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';

$featureDefaults = [
    'mensal' => ['Musculação e cardio', 'Área de pesos livres'],
    'trimestral' => ['Musculação e cardio', 'Área de pesos livres', 'Aulas coletivas'],
    'anual' => ['Acesso completo à academia', 'Aulas coletivas ilimitadas', 'Melhor custo-benefício'],
    'premium' => ['Acesso total à academia', 'Aulas coletivas ilimitadas', 'Acompanhamento personalizado', 'Benefícios exclusivos'],
    'estudante' => ['Acesso à musculação e cardio', 'Condição especial para estudantes'],
];
?>
<div class="page-head">
    <div>
        <h1 class="page-title">Planos</h1>
        <p class="subtitle">Configure os planos oferecidos pela academia.</p>
    </div>
    <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
        <div class="page-actions">
            <a href="<?= $basePath ?>/planos/create" class="btn btn-primary"><?= UI::icon('plus', 18) ?> Novo Plano</a>
        </div>
    <?php endif; ?>
</div>

<?php if (!$planos): ?>
    <div class="panel empty-state">Nenhum plano cadastrado.</div>
<?php else: ?>
    <div class="plans-grid">
        <?php foreach ($planos as $plano):
            $nome = (string)$plano['nome'];
            $key = strtolower(trim($nome));
            $isPopular = str_contains($key, 'premium');
            $status = strtolower((string)($plano['status'] ?? 'ativo'));
            $descricao = trim((string)($plano['descricao'] ?? ''));

            // Se a descrição já estiver em linhas/itens, usa esses itens. Caso contrário,
            // mantém a descrição como texto e usa benefícios coerentes com o protótipo.
            $parts = preg_split('/[\r\n;]+/', $descricao) ?: [];
            $parts = array_values(array_filter(array_map('trim', $parts)));
            $features = count($parts) > 1 ? $parts : ($featureDefaults[$key] ?? ['Acesso à academia', 'Benefícios conforme o plano']);
        ?>
            <article class="plan-card <?= $isPopular ? 'popular' : '' ?>">
                <?php if ($isPopular): ?><span class="plan-popular-badge">POPULAR</span><?php endif; ?>

                <div class="plan-header">
                    <div class="plan-title-line">
                        <h3><?= htmlspecialchars($nome) ?></h3>
                        <span class="badge badge-<?= $status === 'ativo' ? 'success' : 'secondary' ?>"><?= ucfirst($status) ?></span>
                    </div>
                    <p class="plan-description"><?= htmlspecialchars($descricao ?: 'Acesso completo conforme as condições do plano.') ?></p>
                    <div class="plan-price">
                        R$ <?= number_format((float)$plano['valor'], 2, ',', '.') ?>
                        <span>/ <?= (int)$plano['duracao_meses'] ?> <?= (int)$plano['duracao_meses'] === 1 ? 'mês' : 'meses' ?></span>
                    </div>
                </div>

                <ul class="plan-features">
                    <?php foreach (array_slice($features, 0, 4) as $feature): ?>
                        <li><?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($usuarioLogado && in_array($usuarioLogado['perfil'], ['admin', 'recepcionista'], true)): ?>
                    <div class="plan-actions">
                        <a class="btn btn-secondary" href="<?= $basePath ?>/planos/edit?id=<?= (int)$plano['id'] ?>">Editar</a>
                        <form action="<?= $basePath ?>/planos/delete" method="post" onsubmit="return confirm('Excluir este plano?')">
                            <input type="hidden" name="id" value="<?= (int)$plano['id'] ?>">
                            <button class="text-danger" type="submit">Excluir</button>
                        </form>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
