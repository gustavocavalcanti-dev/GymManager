<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GymManager</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-left">
            <div class="login-left-brand">
                💪 GymManager
            </div>
            <div class="login-left-content">
                <h1>Gestão completa da sua academia em um só lugar.</h1>
                <p>Alunos, planos, matrículas, treinos, pagamentos e relatórios — com uma experiência moderna e responsiva.</p>
                <div class="login-left-features">
                    <div>✓ Dashboard em tempo real</div>
                    <div>✓ Controle financeiro</div>
                    <div>✓ Treinos personalizados</div>
                    <div>✓ Relatórios avançados</div>
                </div>
            </div>
            <div class="login-left-footer">
                &copy; <?= date('Y') ?> GymManager. Todos os direitos reservados.
            </div>
        </div>
        <div class="login-right">
            <div class="login-form-wrapper">
                <h2>Bem-vindo de volta</h2>
                <p class="login-subtitle">Entre com suas credenciais para acessar o painel.</p>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars((string) $erro) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($sucesso)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars((string) $sucesso) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= $basePath ?>/login" method="post">
                    <?= Security::campoCSRF() ?>
                    <div class="form-group">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" required placeholder="admin@gymmanager.com">
                    </div>
                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label for="senha" class="form-label" style="margin-bottom: 0;">Senha</label>
                            <a href="#" style="font-size: 0.8rem; color: var(--primary); text-decoration: none;">Esqueci minha senha</a>
                        </div>
                        <input type="password" name="senha" id="senha" class="form-control" required placeholder="••••••••">
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="lembrar" name="lembrar" style="width: 16px; height: 16px;">
                        <label for="lembrar" style="font-size: 0.85rem; color: var(--text-color); cursor: pointer;">Lembrar-me neste dispositivo</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="padding: 12px;">Entrar</button>
                </form>

                <div style="margin-top: 24px; text-align: center; font-size: 0.85rem; color: var(--text-muted);">
                    Novo por aqui? <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 500;">Fale com o administrador</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
