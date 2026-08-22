<?php

declare(strict_types=1);



class LoginController extends Controller
{
    public function index(): void
    {
        if ($this->usuarioLogado() !== null) {
            $this->redirect('/');
            return;
        }
        $this->view('Login/index');
    }

    public function autenticar(): void
    {
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::validarTokenCSRF($csrfToken)) {
            $this->setFlash('erro', 'Token de segurança inválido. Tente novamente.');
            $this->redirect('/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $this->setFlash('erro', 'Preencha o e-mail e a senha.');
            $this->redirect('/login');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('erro', 'Informe um e-mail válido.');
            $this->redirect('/login');
            return;
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->buscarPorEmail($email);

        if ($usuario === null) {
            $this->setFlash('erro', 'E-mail ou senha incorretos.');
            $this->redirect('/login');
            return;
        }

        if (!Security::verificarSenha($senha, $usuario['senha'])) {
            $this->setFlash('erro', 'E-mail ou senha incorretos.');
            $this->redirect('/login');
            return;
        }

        if (empty($usuario['ativo'])) {
            $this->setFlash('erro', 'Sua conta está desativada. Entre em contato com o administrador.');
            $this->redirect('/login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id' => (int) $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'perfil' => $usuario['perfil'],
            'ativo' => (bool) $usuario['ativo']
        ];

        $usuarioModel->atualizarUltimoLogin((int) $usuario['id']);

        $this->setFlash('sucesso', 'Bem-vindo(a), ' . $usuario['nome'] . '!');
        $this->redirect('/');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        session_start();
        $this->setFlash('sucesso', 'Você saiu do sistema com sucesso.');
        $this->redirect('/login');
    }
}
