<?php

declare(strict_types=1);

use App\Models\Usuario;

class UsuarioController extends Controller
{
    private Usuario $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function index(): void
    {
        $this->view('Usuarios/index', ['usuarios' => $this->model->listarTodos()]);
    }

    public function create(): void
    {
        $this->view('Usuarios/create', [
            'dados' => [
                'nome' => '', 'email' => '', 'perfil' => 'atendente', 'status' => 'ativo',
            ],
            'erros' => [],
        ]);
    }

    public function store(): void
    {
        $this->validarCSRF('/usuarios');

        $dados = [
            'nome' => trim((string)($_POST['nome'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'senha' => (string)($_POST['senha'] ?? ''),
            'perfil' => $this->normalizarPerfil((string)($_POST['perfil'] ?? 'atendente')),
            'status' => $this->normalizarStatus((string)($_POST['status'] ?? 'ativo')),
        ];
        $confirmarSenha = (string)($_POST['confirmar_senha'] ?? '');

        $erros = $this->validarDados($dados, $confirmarSenha);

        if ($this->model->emailExiste($dados['email'])) {
            $erros[] = 'Este e-mail já está cadastrado.';
        }

        if ($erros !== []) {
            $dadosSemSenha = $dados;
            unset($dadosSemSenha['senha']);
            $this->view('Usuarios/create', ['dados' => $dadosSemSenha, 'erros' => $erros]);
            return;
        }

        try {
            $this->model->criarUsuario($dados);
            $this->setFlash('sucesso', 'Usuário cadastrado com sucesso!');
            $this->redirect('/usuarios');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao cadastrar usuário: ' . $e->getMessage());
            $this->redirect('/usuarios');
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $usuario = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$usuario) {
            $this->setFlash('erro', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
        }

        $this->view('Usuarios/edit', ['usuario' => $usuario, 'erros' => []]);
    }

    public function update(): void
    {
        $this->validarCSRF('/usuarios');

        $id = (int)($_POST['id'] ?? 0);
        $usuarioAtual = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$usuarioAtual) {
            $this->setFlash('erro', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
        }

        $dados = [
            'nome' => trim((string)($_POST['nome'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'perfil' => $this->normalizarPerfil((string)($_POST['perfil'] ?? 'atendente')),
            'status' => $this->normalizarStatus((string)($_POST['status'] ?? 'ativo')),
        ];

        $senha = (string)($_POST['senha'] ?? '');
        $confirmarSenha = (string)($_POST['confirmar_senha'] ?? '');
        $erros = $this->validarDadosEdicao($dados, $senha, $confirmarSenha);

        if ($this->model->emailExiste($dados['email'], $id)) {
            $erros[] = 'Este e-mail já está cadastrado para outro usuário.';
        }

        $logado = $this->usuarioLogado();
        if ((int)($logado['id'] ?? 0) === $id && $dados['status'] !== 'ativo') {
            $erros[] = 'Você não pode desativar o próprio usuário enquanto está logado.';
        }

        if ($erros !== []) {
            $this->view('Usuarios/edit', [
                'usuario' => array_merge($usuarioAtual, $dados),
                'erros' => $erros,
            ]);
            return;
        }

        try {
            $this->model->atualizar($id, $dados);

            if ($senha !== '') {
                $this->model->alterarSenha($id, $senha);
            }

            if ((int)($logado['id'] ?? 0) === $id) {
                $_SESSION['usuario']['nome'] = $dados['nome'];
                $_SESSION['usuario']['email'] = $dados['email'];
                $_SESSION['usuario']['perfil'] = $dados['perfil'];
                $_SESSION['usuario']['status'] = $dados['status'];
            }

            $this->setFlash('sucesso', 'Usuário atualizado com sucesso!');
            $this->redirect('/usuarios');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao atualizar usuário: ' . $e->getMessage());
            $this->redirect('/usuarios');
        }
    }

    public function delete(): void
    {
        $this->validarCSRF('/usuarios');

        $id = (int)($_POST['id'] ?? 0);
        $logado = $this->usuarioLogado();

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
        }

        if ((int)($logado['id'] ?? 0) === $id) {
            $this->setFlash('erro', 'Você não pode excluir o próprio usuário.');
            $this->redirect('/usuarios');
        }

        try {
            $this->model->excluir($id);
            $this->setFlash('sucesso', 'Usuário excluído com sucesso!');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir usuário: ' . $e->getMessage());
        }

        $this->redirect('/usuarios');
    }

    public function toggleStatus(): void
    {
        $this->validarCSRF('/usuarios');

        $id = (int)($_POST['id'] ?? 0);
        $logado = $this->usuarioLogado();

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
        }

        if ((int)($logado['id'] ?? 0) === $id) {
            $this->setFlash('erro', 'Você não pode desativar o próprio usuário.');
            $this->redirect('/usuarios');
        }

        $this->model->alternarStatus($id);
        $this->setFlash('sucesso', 'Status do usuário alterado com sucesso!');
        $this->redirect('/usuarios');
    }

    public function perfil(): void
    {
        $logado = $this->usuarioLogado();
        $usuario = $logado ? $this->model->buscarPorId((int)$logado['id']) : false;

        if (!$usuario) {
            $this->redirect('/logout');
        }

        $this->view('Usuarios/perfil', ['usuario' => $usuario, 'erros' => []]);
    }

    public function atualizarPerfil(): void
    {
        $this->validarCSRF('/perfil');

        $logado = $this->usuarioLogado();
        $id = (int)($logado['id'] ?? 0);
        $usuarioAtual = $this->model->buscarPorId($id);

        if (!$usuarioAtual) {
            $this->redirect('/logout');
        }

        $dados = [
            'nome' => trim((string)($_POST['nome'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
        ];

        $senhaAtual = (string)($_POST['senha_atual'] ?? '');
        $novaSenha = (string)($_POST['nova_senha'] ?? '');
        $confirmarSenha = (string)($_POST['confirmar_senha'] ?? '');

        $erros = [];

        if (strlen($dados['nome']) < 3) $erros[] = 'O nome deve ter pelo menos 3 caracteres.';
        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) $erros[] = 'Informe um e-mail válido.';
        if ($this->model->emailExiste($dados['email'], $id)) $erros[] = 'Este e-mail já está cadastrado para outro usuário.';

        if ($novaSenha !== '') {
            if (!Security::verificarSenha($senhaAtual, (string)$usuarioAtual['senha'])) {
                $erros[] = 'A senha atual está incorreta.';
            }
            if (strlen($novaSenha) < 6) $erros[] = 'A nova senha deve ter pelo menos 6 caracteres.';
            if ($novaSenha !== $confirmarSenha) $erros[] = 'A confirmação da nova senha não confere.';
        }

        if ($erros !== []) {
            $this->view('Usuarios/perfil', [
                'usuario' => array_merge($usuarioAtual, $dados),
                'erros' => $erros,
            ]);
            return;
        }

        $this->model->atualizar($id, $dados);

        if ($novaSenha !== '') {
            $this->model->alterarSenha($id, $novaSenha);
        }

        $_SESSION['usuario']['nome'] = $dados['nome'];
        $_SESSION['usuario']['email'] = $dados['email'];

        $this->setFlash('sucesso', 'Perfil atualizado com sucesso!');
        $this->redirect('/perfil');
    }

    private function validarDados(array $dados, string $confirmarSenha): array
    {
        $erros = [];

        if (strlen($dados['nome']) < 3) $erros[] = 'O nome deve ter pelo menos 3 caracteres.';
        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) $erros[] = 'Informe um e-mail válido.';
        if (strlen($dados['senha']) < 6) $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
        if ($dados['senha'] !== $confirmarSenha) $erros[] = 'A confirmação de senha não confere.';

        return $erros;
    }

    private function validarDadosEdicao(array $dados, string $senha, string $confirmarSenha): array
    {
        $erros = [];

        if (strlen($dados['nome']) < 3) $erros[] = 'O nome deve ter pelo menos 3 caracteres.';
        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) $erros[] = 'Informe um e-mail válido.';

        if ($senha !== '') {
            if (strlen($senha) < 6) $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
            if ($senha !== $confirmarSenha) $erros[] = 'A confirmação de senha não confere.';
        }

        return $erros;
    }

    private function normalizarPerfil(string $perfil): string
    {
        return in_array($perfil, ['administrador', 'atendente', 'professor'], true)
            ? $perfil
            : 'atendente';
    }

    private function normalizarStatus(string $status): string
    {
        return in_array($status, ['ativo', 'inativo'], true) ? $status : 'ativo';
    }
}
