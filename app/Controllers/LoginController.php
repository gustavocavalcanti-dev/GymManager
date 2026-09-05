<?php

declare(strict_types=1);

use App\Models\Usuario;

class LoginController extends Controller
{
    public function index(): void
    {
        if ($this->usuarioLogado() !== null) {
            $this->redirect('/');
        }

        $this->view('Login/index');
    }

    public function autenticar(): void
    {
        $this->validarCSRF('/login');

        $email = trim((string)($_POST['email'] ?? ''));
        $senha = (string)($_POST['senha'] ?? '');

        if ($email === '' || $senha === '') {
            $this->setFlash('erro', 'Preencha o e-mail e a senha.');
            $this->redirect('/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('erro', 'Informe um e-mail válido.');
            $this->redirect('/login');
        }

        $model = new Usuario();
        $usuario = $model->buscarPorEmail($email);

        if ($usuario === null || !Security::verificarSenha($senha, (string)$usuario['senha'])) {
            $this->setFlash('erro', 'E-mail ou senha incorretos.');
            $this->redirect('/login');
        }

        if (($usuario['status'] ?? 'inativo') !== 'ativo') {
            $this->setFlash('erro', 'Sua conta está inativa. Entre em contato com o administrador.');
            $this->redirect('/login');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id' => (int)$usuario['id'],
            'nome' => (string)$usuario['nome'],
            'email' => (string)$usuario['email'],
            'perfil' => (string)$usuario['perfil'],
            'status' => (string)$usuario['status'],
        ];

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
                (bool)$params['secure'],
                (bool)$params['httponly']
            );
        }

        session_destroy();
        session_start();

        $this->setFlash('sucesso', 'Você saiu do sistema com sucesso.');
        $this->redirect('/login');
    }
}
