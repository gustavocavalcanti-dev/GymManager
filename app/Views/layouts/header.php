<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
$usuarioLogado = $_SESSION['usuario'] ?? null;
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if ($basePath !== '' && stripos($currentUri, $basePath) === 0) {
    $currentUri = substr($currentUri, strlen($basePath));
}
$currentUri = '/' . trim($currentUri, '/');
if ($currentUri === '//') $currentUri = '/';
require_once dirname(__DIR__, 2) . '/Helpers/UI.php';

$appConfig = $appConfig ?? [];
$primaryColor = (string)($appConfig['cor_primaria'] ?? '#2164E9');
if (!preg_match('/^#[0-9a-fA-F]{6}$/', $primaryColor)) $primaryColor = '#2164E9';
$defaultDark = (($appConfig['tema'] ?? 'claro') === 'escuro');
$logoPath = (string)($appConfig['logo_path'] ?? '');
$logoVisual = $logoPath !== '' && !str_ends_with(strtolower($logoPath), '.pdf');
$roleLabels = ['administrador' => 'Administrador', 'professor' => 'Professor', 'atendente' => 'Recepcionista'];

$navItems = [
    ['/', 'dashboard', 'Dashboard'],
    ['/alunos', 'users', 'Alunos'],
    ['/professores', 'teacher', 'Professores'],
    ['/planos', 'box', 'Planos'],
    ['/matriculas', 'clipboard', 'Matrículas'],
    ['/treinos', 'dumbbell', 'Treinos'],
    ['/pagamentos', 'card', 'Pagamentos'],
];
if ($usuarioLogado && ($usuarioLogado['perfil'] ?? '') === 'administrador') {
    $navItems[] = ['/relatorios', 'chart', 'Relatórios'];
    $navItems[] = ['/usuarios', 'user-settings', 'Usuários'];
    $navItems[] = ['/configuracoes', 'settings', 'Configurações'];
}

function gmNavActive(string $currentUri, string $route): bool {
    if ($route === '/') return $currentUri === '/';
    return stripos($currentUri, $route) === 0;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymManager</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css?v=20260905-final">
    <style>:root{--primary:<?= htmlspecialchars($primaryColor) ?>}</style>
</head>
<body<?= $defaultDark ? ' class="dark"' : '' ?>>
<div class="app-layout">
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand-wrap">
                <?php if ($logoVisual): ?>
                    <span class="brand-mark brand-mark-image"><img src="<?= htmlspecialchars($basePath . $logoPath) ?>" alt="Logo"></span>
                <?php else: ?><span class="brand-mark"><?= UI::icon('logo', 24) ?></span><?php endif; ?>
                <div class="sidebar-brand-copy">
                    <strong>GymManager</strong>
                    <span>Gestão de Academias</span>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <?php foreach ($navItems as [$route, $icon, $label]): ?>
                <a href="<?= $basePath . $route ?>" class="<?= gmNavActive($currentUri, $route) ? 'active' : '' ?>">
                    <?= UI::icon($icon, 18) ?>
                    <span><?= htmlspecialchars($label) ?></span>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-footer">
            <?php if ($usuarioLogado): ?>
                <span class="avatar"><?= htmlspecialchars(UI::initials((string) $usuarioLogado['nome'])) ?></span>
                <div class="user-meta">
                    <p class="user-name"><?= htmlspecialchars((string) $usuarioLogado['nome']) ?></p>
                    <p class="user-role"><?= htmlspecialchars($roleLabels[(string)$usuarioLogado['perfil']] ?? ucfirst((string)$usuarioLogado['perfil'])) ?></p>
                </div>
                <a class="logout-icon" href="<?= $basePath ?>/logout" title="Sair"><?= UI::icon('logout', 18) ?></a>
            <?php endif; ?>
        </div>
    </aside>

    <header class="topbar">
        <button class="icon-btn mobile-menu-btn" type="button" data-menu-toggle aria-label="Abrir menu"><?= UI::icon('menu', 20) ?></button>
        <div class="global-search">
            <?= UI::icon('search', 19) ?>
            <input data-global-search data-base="<?= htmlspecialchars($basePath) ?>" type="search" placeholder="Buscar alunos, planos, pagamentos..." autocomplete="off">
        </div>
        <div class="topbar-actions">
            <button class="icon-btn" type="button" data-theme-toggle title="Alternar tema"><?= UI::icon('moon', 19) ?></button>
            <button class="icon-btn" type="button" title="Notificações">
                <?= UI::icon('bell', 19) ?><span class="notification-dot"></span>
            </button>
            <?php if ($usuarioLogado): ?>
                <a class="topbar-user" href="<?= $basePath ?>/perfil">
                    <span class="avatar avatar-lg"><?= htmlspecialchars(UI::initials((string) $usuarioLogado['nome'])) ?></span>
                    <span class="topbar-user-copy">
                        <strong><?= htmlspecialchars((string) $usuarioLogado['nome']) ?></strong>
                        <span><?= htmlspecialchars($roleLabels[(string)$usuarioLogado['perfil']] ?? ucfirst((string)$usuarioLogado['perfil'])) ?></span>
                    </span>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <main class="main-content">
        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success"><?= htmlspecialchars((string) $sucesso) ?></div>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars((string) $erro) ?></div>
        <?php endif; ?>
