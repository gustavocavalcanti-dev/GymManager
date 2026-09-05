<?php

declare(strict_types=1);

use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Plano;

class MatriculaController extends Controller
{
    private Matricula $model;
    private Aluno $alunoModel;
    private Plano $planoModel;

    public function __construct()
    {
        $this->model = new Matricula();
        $this->alunoModel = new Aluno();
        $this->planoModel = new Plano();
    }

    public function index(): void
    {
        $this->view('Matriculas/index', ['matriculas' => $this->model->listarComDetalhes()]);
    }

    public function create(): void
    {
        $this->view('Matriculas/create', [
            'dados' => [
                'aluno_id' => '', 'plano_id' => '', 'data_inicio' => date('Y-m-d'),
                'data_fim' => '', 'status' => 'ativa',
            ],
            'alunos' => $this->alunoModel->listarTodos(),
            'planos' => $this->planoModel->listarAtivos(),
            'erros' => [],
        ]);
    }

    public function store(): void
    {
        $this->validarCSRF('/matriculas');
        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($erros !== []) {
            $this->renderFormulario('Matriculas/create', $dados, $erros);
            return;
        }

        try {
            $this->model->cadastrar($dados);
            $this->setFlash('sucesso', 'Matrícula cadastrada com sucesso!');
            $this->redirect('/matriculas');
        } catch (\Throwable $e) {
            $this->renderFormulario('Matriculas/create', $dados, ['Erro ao cadastrar matrícula: ' . $e->getMessage()]);
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $matricula = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$matricula) {
            $this->setFlash('erro', 'Matrícula não encontrada.');
            $this->redirect('/matriculas');
        }

        $this->view('Matriculas/edit', [
            'matricula' => $matricula,
            'alunos' => $this->alunoModel->listarTodos(),
            'planos' => $this->planoModel->listarTodos(),
            'erros' => [],
        ]);
    }

    public function update(): void
    {
        $this->validarCSRF('/matriculas');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Matrícula não encontrada.');
            $this->redirect('/matriculas');
        }

        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($erros !== []) {
            $this->view('Matriculas/edit', [
                'matricula' => array_merge(['id' => $id], $dados),
                'alunos' => $this->alunoModel->listarTodos(),
                'planos' => $this->planoModel->listarTodos(),
                'erros' => $erros,
            ]);
            return;
        }

        try {
            $this->model->atualizar($id, $dados);
            $this->setFlash('sucesso', 'Matrícula atualizada com sucesso!');
            $this->redirect('/matriculas');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao atualizar matrícula: ' . $e->getMessage());
            $this->redirect('/matriculas');
        }
    }

    public function delete(): void
    {
        $this->validarCSRF('/matriculas');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Matrícula não encontrada.');
            $this->redirect('/matriculas');
        }

        try {
            $this->model->excluir($id);
            $this->setFlash('sucesso', 'Matrícula excluída com sucesso!');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Não foi possível excluir a matrícula: ' . $e->getMessage());
        }

        $this->redirect('/matriculas');
    }

    private function dadosFormulario(): array
    {
        $status = (string)($_POST['status'] ?? 'ativa');

        return [
            'aluno_id' => (int)($_POST['aluno_id'] ?? 0),
            'plano_id' => (int)($_POST['plano_id'] ?? 0),
            'data_inicio' => (string)($_POST['data_inicio'] ?? ''),
            'data_fim' => (string)($_POST['data_fim'] ?? ''),
            'status' => in_array($status, ['ativa', 'vencida', 'cancelada'], true) ? $status : 'ativa',
        ];
    }

    private function validarDados(array $dados): array
    {
        $erros = [];

        if ($dados['aluno_id'] <= 0 || !$this->alunoModel->buscarPorId($dados['aluno_id'])) {
            $erros[] = 'Selecione um aluno válido.';
        }

        if ($dados['plano_id'] <= 0 || !$this->planoModel->buscarPorId($dados['plano_id'])) {
            $erros[] = 'Selecione um plano válido.';
        }

        if (strtotime($dados['data_inicio']) === false || strtotime($dados['data_fim']) === false) {
            $erros[] = 'Informe datas válidas.';
        } elseif ($dados['data_fim'] < $dados['data_inicio']) {
            $erros[] = 'A data final não pode ser anterior à data inicial.';
        }

        return $erros;
    }

    private function renderFormulario(string $view, array $dados, array $erros): void
    {
        $this->view($view, [
            'dados' => $dados,
            'alunos' => $this->alunoModel->listarTodos(),
            'planos' => $this->planoModel->listarAtivos(),
            'erros' => $erros,
        ]);
    }
}
