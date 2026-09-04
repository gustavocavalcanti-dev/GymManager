<?php

declare(strict_types=1);

require_once __DIR__ . '/../Core/Model.php';




class UsuarioController extends Controller
{
    protected UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index(): void
    {
        $usuarios = $this->usuarioModel->listarTodos();
        $this->view('Usuarios/index', [
            'usuarios' => $usuarios
        ]);
    }

    public function create(): void
    {
        $this->view('Usuarios/create', [
            'dados' => [
                'nome' => '',
                'email' => '',
                'perfil' => 'recepcionista'
            ],
            'erros' => []
        ]);
    }

    public function store(): void
    {
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::validarTokenCSRF($csrfToken)) {
            $this->setFlash('erro', 'Token de segurança inválido.');
            $this->redirect('/usuarios');
            return;
        }

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'senha' => $_POST['senha'] ?? '',
            'perfil' => $_POST['perfil'] ?? 'recepcionista'
        ];

        $confirmarSenha = $_POST['confirmar_senha'] ?? '';
        $erros = $this->validarDados($dados, $confirmarSenha);

        if (!empty($erros)) {
            $this->view('Usuarios/create', [
                'dados' => $dados,
                'erros' => $erros
            ]);
            return;
        }

        if ($this->usuarioModel->emailExiste($dados['email'])) {
            $this->view('Usuarios/create', [
                'dados' => $dados,
                'erros' => ['Este e-mail já está cadastrado no sistema.']
            ]);
            return;
        }

        try {
            $resultado = $this->usuarioModel->criarUsuario([
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'senha' => $dados['senha'],
                'perfil' => $dados['perfil']
            ]);

            if ($resultado) {
                $this->setFlash('sucesso', 'Usuário cadastrado com sucesso!');
                $this->redirect('/usuarios');
                return;
            }

            $this->view('Usuarios/create', [
                'dados' => $dados,
                'erros' => ['Não foi possível salvar o usuário.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Usuarios/create', [
                'dados' => $dados,
                'erros' => ['Erro ao cadastrar usuário: ' . $e->getMessage()]
            ]);
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('erro', 'Código do usuário inválido.');
            $this->redirect('/usuarios');
            return;
        }

        $usuario = $this->usuarioModel->buscaPorId($id);

        if (!$usuario) {
            $this->setFlash('erro', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
            return;
        }

        $this->view('Usuarios/edit', [
            'usuario' => $usuario,
            'erros' => []
        ]);
    }

    public function update(): void
    {
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::validarTokenCSRF($csrfToken)) {
            $this->setFlash('erro', 'Token de segurança inválido.');
            $this->redirect('/usuarios');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('erro', 'Código do usuário inválido.');
            $this->redirect('/usuarios');
            return;
        }

        $usuarioAtual = $this->usuarioModel->buscaPorId($id);
        if (!$usuarioAtual) {
            $this->setFlash('erro', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
            return;
        }

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'perfil' => $_POST['perfil'] ?? $usuarioAtual['perfil']
        ];

        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        $erros = $this->validarDadosEdicao($dados, $senha, $confirmarSenha);

        if (!empty($erros)) {
            $this->view('Usuarios/edit', [
                'usuario' => array_merge(['id' => $id], $dados),
                'erros' => $erros
            ]);
            return;
        }

        if ($this->usuarioModel->emailExiste($dados['email'], $id)) {
            $this->view('Usuarios/edit', [
                'usuario' => array_merge(['id' => $id], $dados),
                'erros' => ['Este e-mail já está cadastrado para outro usuário.']
            ]);
            return;
        }

        try {
            $resultado = $this->usuarioModel->editar($id, $dados);

            if (!empty($senha)) {
                $this->usuarioModel->alterarSenha($id, $senha);
            }

            if ($resultado) {
                $this->setFlash('sucesso', 'Usuário atualizado com sucesso!');
                $this->redirect('/usuarios');
                return;
            }

            $this->view('Usuarios/edit', [
                'usuario' => array_merge(['id' => $id], $dados),
                'erros' => ['Não foi possível atualizar o usuário.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Usuarios/edit', [
                'usuario' => array_merge(['id' => $id], $dados),
                'erros' => ['Erro ao atualizar usuário: ' . $e->getMessage()]
            ]);
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('erro', 'Código do usuário inválido.');
            $this->redirect('/usuarios');
            return;
        }

        $logado = $this->usuarioLogado();
        if ($logado && $logado['id'] === $id) {
            $this->setFlash('erro', 'Você não pode excluir seu próprio usuário.');
            $this->redirect('/usuarios');
            return;
        }

        $usuario = $this->usuarioModel->buscaPorId($id);
        if (!$usuario) {
            $this->setFlash('erro', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
            return;
        }

        try {
            $resultado = $this->usuarioModel->excluir($id);
            if ($resultado) {
                $this->setFlash('sucesso', 'Usuário excluído com sucesso!');
            } else {
                $this->setFlash('erro', 'Não foi possível excluir o usuário.');
            }
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir usuário: ' . $e->getMessage());
        }

        $this->redirect('/usuarios');
    }

    public function toggleStatus(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('erro', 'Código do usuário inválido.');
            $this->redirect('/usuarios');
            return;
        }

        $logado = $this->usuarioLogado();
        if ($logado && $logado['id'] === $id) {
            $this->setFlash('erro', 'Você não pode alterar o status do seu próprio usuário.');
            $this->redirect('/usuarios');
            return;
        }

        try {
            $this->usuarioModel->alternarStatus($id);
            $this->setFlash('sucesso', 'Status do usuário alterado com sucesso!');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao alterar status: ' . $e->getMessage());
        }

        $this->redirect('/usuarios');
    }

    public function perfil(): void
    {
        $logado = $this->usuarioLogado();
        $usuario = $this->usuarioModel->buscaPorId($logado['id']);

        $this->view('Usuarios/perfil', [
            'usuario' => $usuario,
            'erros' => []
        ]);
    }

    public function atualizarPerfil(): void
    {
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::validarTokenCSRF($csrfToken)) {
            $this->setFlash('erro', 'Token de segurança inválido.');
            $this->redirect('/perfil');
            return;
        }

        $logado = $this->usuarioLogado();
        $id = $logado['id'];

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];

        $senhaAtual = $_POST['senha_atual'] ?? '';
        $novaSenha = $_POST['nova_senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        $erros = [];

        if (empty($dados['nome'])) {
            $erros[] = 'O campo Nome é obrigatório.';
        } elseif (strlen($dados['nome']) < 3) {
            $erros[] = 'O Nome deve ter pelo menos 3 caracteres.';
        }

        if (empty($dados['email'])) {
            $erros[] = 'O campo E-mail é obrigatório.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um e-mail válido.';
        }

        if (!empty($novaSenha)) {
            $usuarioAtual = $this->usuarioModel->buscaPorId($id);

            if (empty($senhaAtual)) {
                $erros[] = 'Informe a senha atual para alterar a senha.';
            } elseif (!Security::verificarSenha($senhaAtual, $usuarioAtual['senha'])) {
                $erros[] = 'A senha atual está incorreta.';
            }

            if (strlen($novaSenha) < 6) {
                $erros[] = 'A nova senha deve ter pelo menos 6 caracteres.';
            }

            if ($novaSenha !== $confirmarSenha) {
                $erros[] = 'A confirmação de senha não confere.';
            }
        }

        if ($this->usuarioModel->emailExiste($dados['email'], $id)) {
            $erros[] = 'Este e-mail já está cadastrado para outro usuário.';
        }

        if (!empty($erros)) {
            $usuario = $this->usuarioModel->buscaPorId($id);
            $usuario['nome'] = $dados['nome'];
            $usuario['email'] = $dados['email'];

            $this->view('Usuarios/perfil', [
                'usuario' => $usuario,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $this->usuarioModel->editar($id, $dados);

            if (!empty($novaSenha)) {
                $this->usuarioModel->alterarSenha($id, $novaSenha);
            }

            $_SESSION['usuario']['nome'] = $dados['nome'];
            $_SESSION['usuario']['email'] = $dados['email'];

            $this->setFlash('sucesso', 'Perfil atualizado com sucesso!');
            $this->redirect('/perfil');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao atualizar perfil: ' . $e->getMessage());
            $this->redirect('/perfil');
        }
    }

    private function validarDados(array $dados, string $confirmarSenha): array
    {
        $erros = [];

        if (empty($dados['nome'])) {
            $erros[] = 'O campo Nome é obrigatório.';
        } elseif (strlen($dados['nome']) < 3) {
            $erros[] = 'O Nome deve ter pelo menos 3 caracteres.';
        }

        if (empty($dados['email'])) {
            $erros[] = 'O campo E-mail é obrigatório.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um e-mail válido.';
        }

        if (empty($dados['senha'])) {
            $erros[] = 'O campo Senha é obrigatório.';
        } elseif (strlen($dados['senha']) < 6) {
            $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
        }

        if ($dados['senha'] !== $confirmarSenha) {
            $erros[] = 'A confirmação de senha não confere.';
        }

        return $erros;
    }

    private function validarDadosEdicao(array $dados, string $senha, string $confirmarSenha): array
    {
        $erros = [];

        if (empty($dados['nome'])) {
            $erros[] = 'O campo Nome é obrigatório.';
        } elseif (strlen($dados['nome']) < 3) {
            $erros[] = 'O Nome deve ter pelo menos 3 caracteres.';
        }

        if (empty($dados['email'])) {
            $erros[] = 'O campo E-mail é obrigatório.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um e-mail válido.';
        }

        if (!empty($senha)) {
            if (strlen($senha) < 6) {
                $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
            }
            if ($senha !== $confirmarSenha) {
                $erros[] = 'A confirmação de senha não confere.';
            }
        }

        return $erros;
    }
}
