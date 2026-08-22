<?php

declare(strict_types=1);




class MatriculaController extends Controller
{
    protected MatriculaModel $matriculaModel;
    protected AlunoModel $alunoModel;
    protected PlanoModel $planoModel;

    public function __construct()
    {
        $this->matriculaModel = new MatriculaModel();
        $this->alunoModel = new AlunoModel();
        $this->planoModel = new PlanoModel();
    }

    public function index(): void
    {
        $matriculas = $this->matriculaModel->listarComDetalhes();
        $this->view('Matriculas/index', [
            'matriculas' => $matriculas
        ]);
    }

    public function create(): void
    {
        $alunos = $this->alunoModel->listarTodos();
        $planos = $this->planoModel->listarTodos();

        $this->view('Matriculas/create', [
            'alunos' => $alunos,
            'planos' => $planos,
            'dados' => [
                'aluno_id' => '',
                'plano_id' => '',
                'data_inicio' => date('Y-m-d'),
                'data_fim' => date('Y-m-d', strtotime('+1 month')),
                'status' => 'ativa'
            ],
            'erros' => []
        ]);
    }

    public function store(): void
    {
        $dados = [
            'aluno_id' => (int) ($_POST['aluno_id'] ?? 0),
            'plano_id' => (int) ($_POST['plano_id'] ?? 0),
            'data_inicio' => trim($_POST['data_inicio'] ?? ''),
            'data_fim' => trim($_POST['data_fim'] ?? ''),
            'status' => trim($_POST['status'] ?? 'ativa')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $alunos = $this->alunoModel->listarTodos();
            $planos = $this->planoModel->listarTodos();
            $this->view('Matriculas/create', [
                'alunos' => $alunos,
                'planos' => $planos,
                'dados' => $dados,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->matriculaModel->cadastrar($dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Matrícula efetuada com sucesso!');
                $this->redirect('/matriculas');
                return;
            }
            $this->view('Matriculas/create', [
                'alunos' => $this->alunoModel->listarTodos(),
                'planos' => $this->planoModel->listarTodos(),
                'dados' => $dados,
                'erros' => ['Não foi possível salvar a matrícula.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Matriculas/create', [
                'alunos' => $this->alunoModel->listarTodos(),
                'planos' => $this->planoModel->listarTodos(),
                'dados' => $dados,
                'erros' => ['Erro ao cadastrar: ' . $e->getMessage()]
            ]);
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código da matrícula inválido.');
            $this->redirect('/matriculas');
            return;
        }

        $matricula = $this->matriculaModel->buscaPorId($id);
        if (!$matricula) {
            $this->setFlash('erro', 'Matrícula não encontrada.');
            $this->redirect('/matriculas');
            return;
        }

        $alunos = $this->alunoModel->listarTodos();
        $planos = $this->planoModel->listarTodos();

        $this->view('Matriculas/edit', [
            'matricula' => $matricula,
            'alunos' => $alunos,
            'planos' => $planos,
            'erros' => []
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código da matrícula inválido.');
            $this->redirect('/matriculas');
            return;
        }

        $matriculaAtual = $this->matriculaModel->buscaPorId($id);
        if (!$matriculaAtual) {
            $this->setFlash('erro', 'Matrícula não encontrada.');
            $this->redirect('/matriculas');
            return;
        }

        $dados = [
            'aluno_id' => (int) ($_POST['aluno_id'] ?? 0),
            'plano_id' => (int) ($_POST['plano_id'] ?? 0),
            'data_inicio' => trim($_POST['data_inicio'] ?? ''),
            'data_fim' => trim($_POST['data_fim'] ?? ''),
            'status' => trim($_POST['status'] ?? 'ativa')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $alunos = $this->alunoModel->listarTodos();
            $planos = $this->planoModel->listarTodos();
            $this->view('Matriculas/edit', [
                'matricula' => array_merge(['id' => $id], $dados),
                'alunos' => $alunos,
                'planos' => $planos,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->matriculaModel->editar($id, $dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Matrícula atualizada com sucesso!');
                $this->redirect('/matriculas');
                return;
            }
            $this->view('Matriculas/edit', [
                'matricula' => array_merge(['id' => $id], $dados),
                'alunos' => $this->alunoModel->listarTodos(),
                'planos' => $this->planoModel->listarTodos(),
                'erros' => ['Não foi possível atualizar.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Matriculas/edit', [
                'matricula' => array_merge(['id' => $id], $dados),
                'alunos' => $this->alunoModel->listarTodos(),
                'planos' => $this->planoModel->listarTodos(),
                'erros' => ['Erro ao atualizar: ' . $e->getMessage()]
            ]);
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código da matrícula inválido.');
            $this->redirect('/matriculas');
            return;
        }

        try {
            $resultado = $this->matriculaModel->excluir($id);
            if ($resultado) {
                $this->setFlash('sucesso', 'Matrícula excluída com sucesso!');
            } else {
                $this->setFlash('erro', 'Não foi possível excluir.');
            }
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir: ' . $e->getMessage());
        }

        $this->redirect('/matriculas');
    }

    private function validarDados(array $dados): array
    {
        $erros = [];
        if ($dados['aluno_id'] <= 0) {
            $erros[] = 'Selecione um Aluno.';
        }
        if ($dados['plano_id'] <= 0) {
            $erros[] = 'Selecione um Plano.';
        }
        if (empty($dados['data_inicio'])) {
            $erros[] = 'Data de início é obrigatória.';
        }
        if (empty($dados['data_fim'])) {
            $erros[] = 'Data de término é obrigatória.';
        }
        if (!empty($dados['data_inicio']) && !empty($dados['data_fim'])) {
            if ($dados['data_fim'] < $dados['data_inicio']) {
                $erros[] = 'A data de término não pode ser anterior à data de início.';
            }
        }
        return $erros;
    }
}
