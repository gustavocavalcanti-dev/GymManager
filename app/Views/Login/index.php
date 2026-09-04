<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
require_once dirname(__DIR__, 2) . '/Helpers/UI.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GymManager</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css?v=20260904-prototipo-v3">
</head>
<body class="login-body">
<div class="login-container">
    <section class="login-left">
        <div class="login-left-brand">
            <span class="brand-mark"><?= UI::icon('logo', 24) ?></span>
            <span class="login-brand-name">GymManager</span>
        </div>

        <div class="login-left-content">
            <h1>Gestão completa da sua academia em um só lugar.</h1>
            <p>Alunos, planos, matrículas, treinos, pagamentos e relatórios — com uma experiência moderna e responsiva.</p>
            <div class="login-left-features">
                <div>Dashboard em tempo real</div>
                <div>Controle financeiro</div>
                <div>Treinos personalizados</div>
                <div>Relatórios avançados</div>
            </div>
        </div>

        <div class="login-left-footer">&copy; <?= date('Y') ?> GymManager. Todos os direitos reservados.</div>
    </section>

    <section class="login-right">
        <div class="login-form-wrapper">
            <h2>Bem-vindo de volta</h2>
            <p class="login-subtitle">Entre com suas credenciais para acessar o painel.</p>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars((string) $erro) ?></div>
            <?php endif; ?>
            <?php if (!empty($sucesso)): ?>
                <div class="alert alert-success"><?= htmlspecialchars((string) $sucesso) ?></div>
            <?php endif; ?>

            <form action="<?= $basePath ?>/login" method="post">
                <?= Security::campoCSRF() ?>
                <div class="form-group">
                    <label class="form-label" for="email">E-mail</label>
                    <div class="login-field">
                        <span class="field-icon"><?= UI::icon('mail', 18) ?></span>
                        <input class="form-control" type="email" id="email" name="email" required autocomplete="username" value="<?= htmlspecialchars((string)($_POST['email'] ?? 'admin@gymmanager.com')) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="login-inline">
                        <label class="form-label" for="senha">Senha</label>
                        <a class="text-link" href="#" onclick="return false;">Esqueci minha senha</a>
                    </div>
                    <div class="login-field">
                        <span class="field-icon"><?= UI::icon('lock', 18) ?></span>
                        <input class="form-control" type="password" id="senha" name="senha" required autocomplete="current-password" placeholder="••••••••••">
                        <button class="toggle-password" type="button" data-toggle-password="senha" aria-label="Mostrar senha"><?= UI::icon('eye', 18) ?></button>
                    </div>
                </div>
                <div class="form-group login-check">
                    <input type="checkbox" id="lembrar" name="lembrar" checked>
                    <label for="lembrar">Lembrar-me neste dispositivo</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>

            <div class="login-help">Novo por aqui? <a href="#" onclick="return false;">Fale com o administrador</a></div>
        </div>
    </section>
</div>
<script src="<?= $basePath ?>/js/app.js?v=20260904-prototipo-v3"></script>
</body>
</html>
