<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
$config = $config ?? [];
$logoPath = (string)($config['logo_path'] ?? '');
?>
<div class="page-head"><div><h1 class="page-title">Configurações</h1><p class="subtitle">Personalize as informações e o comportamento do sistema.</p></div></div>
<div class="config-tabs">
    <button type="button" class="config-tab active" data-config-tab="academia"><?= UI::icon('building',16) ?> Academia</button>
    <button type="button" class="config-tab" data-config-tab="aparencia"><?= UI::icon('palette',16) ?> Aparência</button>
    <button type="button" class="config-tab" data-config-tab="seguranca"><?= UI::icon('shield',16) ?> Segurança</button>
    <button type="button" class="config-tab" data-config-tab="backup"><?= UI::icon('database',16) ?> Backup</button>
</div>
<form method="post" action="<?= $basePath ?>/configuracoes/salvar" enctype="multipart/form-data">
<?= Security::campoCSRF() ?>
<section class="panel config-panel" data-config-panel="academia">
    <h3>Dados da academia</h3><p class="subtitle">Informações exibidas em contratos, recibos e no portal do aluno.</p>
    <div class="form-grid">
        <div><label class="form-label">Nome fantasia</label><input class="form-control" name="nome_fantasia" value="<?= htmlspecialchars((string)($config['nome_fantasia'] ?? 'Academia GymManager')) ?>"></div>
        <div><label class="form-label">CNPJ</label><input class="form-control" name="cnpj" value="<?= htmlspecialchars((string)($config['cnpj'] ?? '12.345.678/0001-90')) ?>" placeholder="12.345.678/0001-90"></div>
        <div><label class="form-label">Telefone</label><input class="form-control" name="telefone" value="<?= htmlspecialchars((string)($config['telefone'] ?? '(00) 00000-0000')) ?>" placeholder="(00) 00000-0000"></div>
        <div><label class="form-label">E-mail</label><input class="form-control" type="email" name="email" value="<?= htmlspecialchars((string)($config['email'] ?? 'contato@gymmanager.com')) ?>"></div>
        <div class="full"><label class="form-label">Endereço</label><input class="form-control" name="endereco" value="<?= htmlspecialchars((string)($config['endereco'] ?? 'Av. Paulista, 1000 - São Paulo/SP')) ?>"></div>
        <div><label class="form-label">Instagram</label><input class="form-control" name="instagram" value="<?= htmlspecialchars((string)($config['instagram'] ?? '@gymmanager')) ?>"></div>
        <div><label class="form-label">Facebook</label><input class="form-control" name="facebook" value="<?= htmlspecialchars((string)($config['facebook'] ?? '/gymmanager')) ?>"></div>
        <div class="full"><label class="form-label">Logo da academia</label></div>
        <label class="upload-box">
            <input type="file" name="logo" accept="image/png,image/jpeg,application/pdf" hidden>
            <div>
                <?php if ($logoPath !== '' && !str_ends_with(strtolower($logoPath), '.pdf')): ?>
                    <img class="logo-preview" src="<?= htmlspecialchars($basePath . $logoPath) ?>" alt="Logo atual"><br>
                <?php else: ?><?= UI::icon('upload',25) ?><?php endif; ?>
                <strong style="display:block;margin-top:10px">Arraste uma imagem ou clique para enviar</strong>
                <small>PNG, JPG ou PDF de até 5MB</small>
            </div>
        </label>
    </div>
</section>
<section class="panel config-panel" data-config-panel="aparencia" hidden>
    <h3>Aparência</h3><p class="subtitle">Preferências visuais do painel.</p>
    <div class="form-grid"><div><label class="form-label">Tema padrão</label><select class="form-control" name="tema"><option value="claro" <?= ($config['tema'] ?? 'claro') === 'claro' ? 'selected' : '' ?>>Claro</option><option value="escuro" <?= ($config['tema'] ?? '') === 'escuro' ? 'selected' : '' ?>>Escuro</option></select></div><div><label class="form-label">Cor principal</label><input class="form-control" name="cor_primaria" value="<?= htmlspecialchars((string)($config['cor_primaria'] ?? '#2164E9')) ?>"></div></div>
</section>
<section class="panel config-panel" data-config-panel="seguranca" hidden>
    <h3>Segurança</h3><p class="subtitle">Informações do ambiente atual.</p>
    <div class="form-grid"><div><label class="form-label">Versão do PHP</label><input class="form-control" disabled value="<?= htmlspecialchars((string)$php_version) ?>"></div><div><label class="form-label">Sessão</label><input class="form-control" disabled value="<?= htmlspecialchars((string)$session_status) ?>"></div><div class="full"><label class="form-label">Servidor</label><input class="form-control" disabled value="<?= htmlspecialchars((string)$server_software) ?>"></div></div>
</section>
<section class="panel config-panel" data-config-panel="backup" hidden>
    <h3>Backup</h3><p class="subtitle">Exporte os dados gerenciais ou use o phpMyAdmin da hospedagem para um backup completo do banco.</p>
    <a class="btn btn-secondary" href="<?= $basePath ?>/relatorios/exportar-excel"><?= UI::icon('download',17) ?> Exportar dados gerenciais</a>
</section>
<div class="config-actions"><a class="btn btn-secondary" href="<?= $basePath ?>/">Cancelar</a><button class="btn btn-primary" type="submit">Salvar alterações</button></div>
</form>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
