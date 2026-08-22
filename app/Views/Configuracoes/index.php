<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Configurações do Sistema</h1>
            <p class="subtitle">Parâmetros operacionais e informações do servidor</p>
        </div>
    </div>

    <div style="margin-top: 16px;">
        <table class="table">
            <tbody>
                <tr>
                    <td style="font-weight: bold; width: 250px;">Versão do PHP</td>
                    <td><?= htmlspecialchars($php_version) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Servidor Web</td>
                    <td><?= htmlspecialchars($server_software) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Status da Sessão</td>
                    <td><?= htmlspecialchars($session_status) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Caminho Base do Sistema (BASE_PATH)</td>
                    <td><?= htmlspecialchars($base_path) ?></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <a href="<?= $basePath ?>/relatorios" class="btn btn-primary">Ver Relatórios Gerenciais</a>
            <a href="<?= $basePath ?>/usuarios" class="btn btn-secondary">Gerenciar Usuários</a>
        </div>
    </div>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
