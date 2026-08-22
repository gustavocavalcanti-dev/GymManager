<?php

declare(strict_types=1);




class ConfiguracaoController extends Controller
{
    public function index(): void
    {
        $this->view('Configuracoes/index', [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'session_status' => session_status() === PHP_SESSION_ACTIVE ? 'Ativa' : 'Inativa',
            'base_path' => defined('BASE_PATH') ? BASE_PATH : 'N/A'
        ]);
    }
}
