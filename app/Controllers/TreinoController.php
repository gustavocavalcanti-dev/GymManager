<?php

declare(strict_types=1);

use App\Models\Aluno;
use App\Models\Professor;
use App\Models\Treino;

class TreinoController extends Controller
{
    private Treino $model;
    private Aluno $alunoModel;
    private Professor $professorModel;

    public function __construct()
    {
        $this->model = new Treino();
        $this->alunoModel = new Aluno();
        $this->professorModel = new Professor();
    }

    public function index(): void
    {
        $this->view('Treinos/index', ['treinos' => $this->model->listarComDetalhes()]);
    }

    public function create(): void
    {
        $this->view('Treinos/create', [
            'dados' => [
                'aluno_id' => '', 'professor_id' => '', 'nome' => '', 'objetivo' => '',
                'descricao' => '', 'data_inicio' => date('Y-m-d'), 'data_fim' => '', 'status' => 'ativo',
            ],
            'alunos' => $this->alunoModel->listarTodos(),
            'professores' => $this->professorModel->listarTodos(),
            'erros' => [],
        ]);
    }

    public function store(): void
    {
        $this->validarCSRF('/treinos');
        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($erros !== []) {
            $this->renderFormulario('Treinos/create', $dados, $erros);
            return;
        }

        try {
            $this->model->cadastrar($dados);
            $this->setFlash('sucesso', 'Treino cadastrado com sucesso!');
            $this->redirect('/treinos');
        } catch (\Throwable $e) {
            $this->renderFormulario('Treinos/create', $dados, ['Erro ao cadastrar treino: ' . $e->getMessage()]);
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $treino = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$treino) {
            $this->setFlash('erro', 'Treino não encontrado.');
            $this->redirect('/treinos');
        }

        $this->view('Treinos/edit', [
            'treino' => $treino,
            'alunos' => $this->alunoModel->listarTodos(),
            'professores' => $this->professorModel->listarTodos(),
            'erros' => [],
        ]);
    }

    public function update(): void
    {
        $this->validarCSRF('/treinos');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Treino não encontrado.');
            $this->redirect('/treinos');
        }

        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($erros !== []) {
            $this->view('Treinos/edit', [
                'treino' => array_merge(['id' => $id], $dados),
                'alunos' => $this->alunoModel->listarTodos(),
                'professores' => $this->professorModel->listarTodos(),
                'erros' => $erros,
            ]);
            return;
        }

        try {
            $this->model->atualizar($id, $dados);
            $this->setFlash('sucesso', 'Treino atualizado com sucesso!');
            $this->redirect('/treinos');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao atualizar treino: ' . $e->getMessage());
            $this->redirect('/treinos');
        }
    }

    public function delete(): void
    {
        $this->validarCSRF('/treinos');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Treino não encontrado.');
            $this->redirect('/treinos');
        }

        try {
            $this->model->excluir($id);
            $this->setFlash('sucesso', 'Treino excluído com sucesso!');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir treino: ' . $e->getMessage());
        }

        $this->redirect('/treinos');
    }

    private function dadosFormulario(): array
    {
        $status = (string)($_POST['status'] ?? 'ativo');
        $dataFim = trim((string)($_POST['data_fim'] ?? ''));

        return [
            'aluno_id' => (int)($_POST['aluno_id'] ?? 0),
            'professor_id' => (int)($_POST['professor_id'] ?? 0),
            'nome' => trim((string)($_POST['nome'] ?? '')),
            'objetivo' => trim((string)($_POST['objetivo'] ?? '')),
            'descricao' => trim((string)($_POST['descricao'] ?? '')),
            'data_inicio' => (string)($_POST['data_inicio'] ?? ''),
            'data_fim' => $dataFim !== '' ? $dataFim : null,
            'status' => in_array($status, ['ativo', 'finalizado'], true) ? $status : 'ativo',
        ];
    }

    private function validarDados(array $dados): array
    {
        $erros = [];

        if ($dados['aluno_id'] <= 0 || !$this->alunoModel->buscarPorId($dados['aluno_id'])) {
            $erros[] = 'Selecione um aluno válido.';
        }

        if ($dados['professor_id'] <= 0 || !$this->professorModel->buscarPorId($dados['professor_id'])) {
            $erros[] = 'Selecione um professor válido.';
        }

        if (strlen($dados['nome']) < 2) {
            $erros[] = 'Informe um nome para o treino.';
        }

        if (strtotime($dados['data_inicio']) === false) {
            $erros[] = 'Informe uma data inicial válida.';
        }

        if ($dados['data_fim'] !== null && $dados['data_fim'] < $dados['data_inicio']) {
            $erros[] = 'A data final não pode ser anterior à data inicial.';
        }

        return $erros;
    }

    private function renderFormulario(string $view, array $dados, array $erros): void
    {
        $this->view($view, [
            'dados' => $dados,
            'alunos' => $this->alunoModel->listarTodos(),
            'professores' => $this->professorModel->listarTodos(),
            'erros' => $erros,
        ]);
    }
}
