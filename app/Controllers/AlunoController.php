<?php

declare(strict_types=1);

use App\Models\Aluno;

class AlunoController extends Controller
{
    private Aluno $model;

    public function __construct()
    {
        $this->model = new Aluno();
    }

    public function index(): void
    {
        $this->view('Alunos/index', ['alunos' => $this->model->listarComPlanoStatus()]);
    }

    public function create(): void
    {
        $this->view('Alunos/create', [
            'dados' => [
                'nome' => '', 'cpf' => '', 'email' => '', 'telefone' => '',
                'data_nascimento' => '', 'endereco' => '', 'status' => 'ativo',
            ],
            'erros' => [],
        ]);
    }

    public function store(): void
    {
        $this->validarCSRF('/alunos');

        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($this->model->cpfExiste($dados['cpf'])) {
            $erros[] = 'Este CPF já está cadastrado.';
        }
        if ($this->model->emailExiste($dados['email'])) {
            $erros[] = 'Este e-mail já está cadastrado.';
        }

        if ($erros !== []) {
            $this->view('Alunos/create', compact('dados', 'erros'));
            return;
        }

        try {
            $this->model->cadastrar($dados);
            $this->setFlash('sucesso', 'Aluno cadastrado com sucesso!');
            $this->redirect('/alunos');
        } catch (\Throwable $e) {
            $erros = ['Erro ao cadastrar aluno: ' . $e->getMessage()];
            $this->view('Alunos/create', compact('dados', 'erros'));
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $aluno = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$aluno) {
            $this->setFlash('erro', 'Aluno não encontrado.');
            $this->redirect('/alunos');
        }

        $this->view('Alunos/edit', ['aluno' => $aluno, 'erros' => []]);
    }

    public function update(): void
    {
        $this->validarCSRF('/alunos');

        $id = (int)($_POST['id'] ?? 0);
        $aluno = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$aluno) {
            $this->setFlash('erro', 'Aluno não encontrado.');
            $this->redirect('/alunos');
        }

        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($this->model->cpfExiste($dados['cpf'], $id)) {
            $erros[] = 'Este CPF já está cadastrado para outro aluno.';
        }
        if ($this->model->emailExiste($dados['email'], $id)) {
            $erros[] = 'Este e-mail já está cadastrado para outro aluno.';
        }

        if ($erros !== []) {
            $this->view('Alunos/edit', [
                'aluno' => array_merge(['id' => $id], $dados),
                'erros' => $erros,
            ]);
            return;
        }

        try {
            $this->model->atualizar($id, $dados);
            $this->setFlash('sucesso', 'Aluno atualizado com sucesso!');
            $this->redirect('/alunos');
        } catch (\Throwable $e) {
            $this->view('Alunos/edit', [
                'aluno' => array_merge(['id' => $id], $dados),
                'erros' => ['Erro ao atualizar aluno: ' . $e->getMessage()],
            ]);
        }
    }

    public function delete(): void
    {
        $this->validarCSRF('/alunos');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Aluno não encontrado.');
            $this->redirect('/alunos');
        }

        try {
            $this->model->excluir($id);
            $this->setFlash('sucesso', 'Aluno excluído com sucesso!');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Não foi possível excluir o aluno: ' . $e->getMessage());
        }

        $this->redirect('/alunos');
    }

    private function dadosFormulario(): array
    {
        return [
            'nome' => trim((string)($_POST['nome'] ?? '')),
            'cpf' => $this->formatarCpf((string)($_POST['cpf'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'telefone' => trim((string)($_POST['telefone'] ?? '')),
            'data_nascimento' => ($_POST['data_nascimento'] ?? '') !== '' ? (string)$_POST['data_nascimento'] : null,
            'endereco' => trim((string)($_POST['endereco'] ?? '')),
            'status' => in_array((string)($_POST['status'] ?? ''), ['ativo', 'inativo'], true)
                ? (string)$_POST['status']
                : 'ativo',
        ];
    }

    private function validarDados(array $dados): array
    {
        $erros = [];

        if (strlen($dados['nome']) < 3) {
            $erros[] = 'Informe o nome completo do aluno.';
        }

        if (strlen(preg_replace('/\D/', '', $dados['cpf'])) !== 11) {
            $erros[] = 'Informe um CPF com 11 dígitos.';
        }

        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um e-mail válido.';
        }

        if ($dados['data_nascimento'] !== null && strtotime($dados['data_nascimento']) === false) {
            $erros[] = 'Informe uma data de nascimento válida.';
        }

        return $erros;
    }

    private function formatarCpf(string $cpf): string
    {
        $n = preg_replace('/\D/', '', $cpf);

        if (strlen($n) !== 11) {
            return trim($cpf);
        }

        return substr($n, 0, 3) . '.' . substr($n, 3, 3) . '.' . substr($n, 6, 3) . '-' . substr($n, 9, 2);
    }
}
