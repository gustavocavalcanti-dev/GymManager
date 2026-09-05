<?php

declare(strict_types=1);

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("A view {$view} não foi encontrada.");
        }

        if (!isset($data['sucesso'])) {
            $data['sucesso'] = $this->getFlash('sucesso');
        }
        if (!isset($data['erro'])) {
            $data['erro'] = $this->getFlash('erro');
        }
        if (!isset($data['erros'])) {
            $data['erros'] = $this->getFlash('erros') ?: [];
        }

        $data['usuarioLogado'] = $this->usuarioLogado();

        if (!isset($data['appConfig']) && class_exists('ConfiguracaoModel')) {
            try {
                $data['appConfig'] = (new ConfiguracaoModel())->todos();
            } catch (\Throwable) {
                $data['appConfig'] = [];
            }
        }

        extract($data);
        require $viewPath;
    }

    protected function setFlash(string $chave, mixed $valor): void
    {
        $this->garantirSessao();
        $_SESSION['flash'][$chave] = $valor;
    }

    protected function getFlash(string $chave): mixed
    {
        $this->garantirSessao();

        if (!array_key_exists($chave, $_SESSION['flash'] ?? [])) {
            return null;
        }

        $valor = $_SESSION['flash'][$chave];
        unset($_SESSION['flash'][$chave]);

        return $valor;
    }

    protected function redirect(string $caminho): never
    {
        $basePath = defined('BASE_PATH') ? BASE_PATH : '';
        $url = $basePath . '/' . ltrim($caminho, '/');
        header('Location: ' . $url);
        exit;
    }

    protected function usuarioLogado(): ?array
    {
        $this->garantirSessao();
        return $_SESSION['usuario'] ?? null;
    }

    protected function temPermissao(string $perfil): bool
    {
        $usuario = $this->usuarioLogado();

        if ($usuario === null) {
            return false;
        }

        return ($usuario['perfil'] ?? '') === 'administrador'
            || ($usuario['perfil'] ?? '') === $perfil;
    }

    protected function validarCSRF(string $redirecionarPara): bool
    {
        $token = (string)($_POST['csrf_token'] ?? '');

        if (Security::validarTokenCSRF($token)) {
            return true;
        }

        $this->setFlash('erro', 'Token de segurança inválido. Atualize a página e tente novamente.');
        $this->redirect($redirecionarPara);
    }

    private function garantirSessao(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
