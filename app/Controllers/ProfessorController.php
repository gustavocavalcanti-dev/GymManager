<?php

declare(strict_types=1);

use App\Models\Professor;

class ProfessorController extends Controller
{
    private Professor $model;

    public function __construct()
    {
        $this->model = new Professor();
    }

    public function index(): void
    {
        $this->view('Professores/index', ['professores' => $this->model->listarTodos()]);
    }

    public function create(): void
    {
        $this->view('Professores/create', [
            'dados' => [
                'nome' => '', 'cpf' => '', 'email' => '', 'telefone' => '',
                'especialidade' => '', 'cref' => '', 'status' => 'ativo',
            ],
            'erros' => [],
        ]);
    }

    public function store(): void
    {
        $this->validarCSRF('/professores');
        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($this->model->cpfExiste($dados['cpf'])) $erros[] = 'Este CPF já está cadastrado.';
        if ($this->model->emailExiste($dados['email'])) $erros[] = 'Este e-mail já está cadastrado.';
        if ($this->model->crefExiste($dados['cref'])) $erros[] = 'Este CREF já está cadastrado.';

        if ($erros !== []) {
            $this->view('Professores/create', compact('dados', 'erros'));
            return;
        }

        $dados['cref'] = $dados['cref'] !== '' ? $dados['cref'] : null;

        try {
            $this->model->cadastrar($dados);
            $this->setFlash('sucesso', 'Professor cadastrado com sucesso!');
            $this->redirect('/professores');
        } catch (\Throwable $e) {
            $erros = ['Erro ao cadastrar professor: ' . $e->getMessage()];
            $this->view('Professores/create', compact('dados', 'erros'));
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $professor = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$professor) {
            $this->setFlash('erro', 'Professor não encontrado.');
            $this->redirect('/professores');
        }

        $this->view('Professores/edit', ['professor' => $professor, 'erros' => []]);
    }

    public function update(): void
    {
        $this->validarCSRF('/professores');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Professor não encontrado.');
            $this->redirect('/professores');
        }

        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($this->model->cpfExiste($dados['cpf'], $id)) $erros[] = 'Este CPF já pertence a outro professor.';
        if ($this->model->emailExiste($dados['email'], $id)) $erros[] = 'Este e-mail já pertence a outro professor.';
        if ($this->model->crefExiste($dados['cref'], $id)) $erros[] = 'Este CREF já pertence a outro professor.';

        if ($erros !== []) {
            $this->view('Professores/edit', [
                'professor' => array_merge(['id' => $id], $dados),
                'erros' => $erros,
            ]);
            return;
        }

        $dados['cref'] = $dados['cref'] !== '' ? $dados['cref'] : null;

        try {
            $this->model->atualizar($id, $dados);
            $this->setFlash('sucesso', 'Professor atualizado com sucesso!');
            $this->redirect('/professores');
        } catch (\Throwable $e) {
            $this->view('Professores/edit', [
                'professor' => array_merge(['id' => $id], $dados),
                'erros' => ['Erro ao atualizar professor: ' . $e->getMessage()],
            ]);
        }
    }

    public function delete(): void
    {
        $this->validarCSRF('/professores');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Professor não encontrado.');
            $this->redirect('/professores');
        }

        try {
            $this->model->excluir($id);
            $this->setFlash('sucesso', 'Professor excluído com sucesso!');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Não foi possível excluir o professor: ' . $e->getMessage());
        }

        $this->redirect('/professores');
    }

    private function dadosFormulario(): array
    {
        return [
            'nome' => trim((string)($_POST['nome'] ?? '')),
            'cpf' => $this->formatarCpf((string)($_POST['cpf'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'telefone' => trim((string)($_POST['telefone'] ?? '')),
            'especialidade' => trim((string)($_POST['especialidade'] ?? '')),
            'cref' => trim((string)($_POST['cref'] ?? '')),
            'status' => in_array((string)($_POST['status'] ?? ''), ['ativo', 'inativo'], true)
                ? (string)$_POST['status']
                : 'ativo',
        ];
    }

    private function validarDados(array $dados): array
    {
        $erros = [];
        if (strlen($dados['nome']) < 3) $erros[] = 'Informe o nome completo do professor.';
        if (strlen(preg_replace('/\D/', '', $dados['cpf'])) !== 11) $erros[] = 'Informe um CPF com 11 dígitos.';
        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) $erros[] = 'Informe um e-mail válido.';
        return $erros;
    }

    private function formatarCpf(string $cpf): string
    {
        $n = preg_replace('/\D/', '', $cpf);
        if (strlen($n) !== 11) return trim($cpf);
        return substr($n,0,3).'.'.substr($n,3,3).'.'.substr($n,6,3).'-'.substr($n,9,2);
    }
}
