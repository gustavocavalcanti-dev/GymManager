<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
$usuarioLogado = $_SESSION['usuario'] ?? null;
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if ($basePath !== '' && stripos($currentUri, $basePath) === 0) {
    $currentUri = substr($currentUri, strlen($basePath));
}
$currentUri = '/' . trim($currentUri, '/');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymManager</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>💪 GymManager</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="<?= $basePath ?>/" class="<?= $currentUri === '/' ? 'active' : '' ?>">Dashboard</a>
                <a href="<?= $basePath ?>/alunos" class="<?= stripos($currentUri, '/alunos') === 0 ? 'active' : '' ?>">Alunos</a>
                <a href="<?= $basePath ?>/professores" class="<?= stripos($currentUri, '/professores') === 0 ? 'active' : '' ?>">Professores</a>
                <a href="<?= $basePath ?>/planos" class="<?= stripos($currentUri, '/planos') === 0 ? 'active' : '' ?>">Planos</a>
                <a href="<?= $basePath ?>/matriculas" class="<?= stripos($currentUri, '/matriculas') === 0 ? 'active' : '' ?>">Matrículas</a>
                <a href="<?= $basePath ?>/treinos" class="<?= stripos($currentUri, '/treinos') === 0 ? 'active' : '' ?>">Treinos</a>
                <a href="<?= $basePath ?>/pagamentos" class="<?= stripos($currentUri, '/pagamentos') === 0 ? 'active' : '' ?>">Pagamentos</a>
                <a href="<?= $basePath ?>/relatorios" class="<?= stripos($currentUri, '/relatorios') === 0 ? 'active' : '' ?>">Relatórios</a>
                <?php if ($usuarioLogado && $usuarioLogado['perfil'] === 'admin'): ?>
                    <a href="<?= $basePath ?>/usuarios" class="<?= stripos($currentUri, '/usuarios') === 0 ? 'active' : '' ?>">Usuários</a>
                    <a href="<?= $basePath ?>/configuracoes" class="<?= stripos($currentUri, '/configuracoes') === 0 ? 'active' : '' ?>">Configurações</a>
                <?php endif; ?>
                <a href="<?= $basePath ?>/perfil" class="nav-profile <?= stripos($currentUri, '/perfil') === 0 ? 'active' : '' ?>">Meu Perfil</a>
                <a href="<?= $basePath ?>/logout" class="nav-logout">Sair</a>
            </nav>
            <div class="sidebar-footer">
                <?php if ($usuarioLogado): ?>
                    <p class="user-name"><?= htmlspecialchars($usuarioLogado['nome']) ?></p>
                    <p class="user-role"><?= ucfirst(htmlspecialchars($usuarioLogado['perfil'])) ?></p>
                <?php endif; ?>
            </div>
        </aside>
        <main class="main-content">
            <?php if (!empty($_SESSION['flash']['sucesso'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['flash']['sucesso']) ?>
                    <?php unset($_SESSION['flash']['sucesso']); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['flash']['erro'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['flash']['erro']) ?>
                    <?php unset($_SESSION['flash']['erro']); ?>
                </div>
            <?php endif; ?>
