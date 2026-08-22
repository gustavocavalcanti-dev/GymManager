<?php

declare(strict_types=1);



class TreinoController extends Controller
{
    protected TreinoModel $treinoModel;
    protected AlunoModel $alunoModel;
    protected ProfessorModel $professorModel;

    public function __construct()
    {
        $this->treinoModel = new TreinoModel();
        $this->alunoModel = new AlunoModel();
        $this->professorModel = new ProfessorModel();
    }

    public function index(): void
    {
        $treinos = $this->treinoModel->listarComDetalhes();
        $this->view('Treinos/index', [
            'treinos' => $treinos
        ]);
    }

    public function create(): void
    {
        $alunos = $this->alunoModel->listarTodos();
        $professores = $this->professorModel->listarTodos();

        $this->view('Treinos/create', [
            'alunos' => $alunos,
            'professores' => $professores,
            'dados' => [
                'aluno_id' => '',
                'professor_id' => '',
                'descricao' => '',
                'dia_semana' => ''
            ],
            'erros' => []
        ]);
    }

    public function store(): void
    {
        $dados = [
            'aluno_id' => (int) ($_POST['aluno_id'] ?? 0),
            'professor_id' => (int) ($_POST['professor_id'] ?? 0),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'dia_semana' => trim($_POST['dia_semana'] ?? '')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $alunos = $this->alunoModel->listarTodos();
            $professores = $this->professorModel->listarTodos();
            $this->view('Treinos/create', [
                'alunos' => $alunos,
                'professores' => $professores,
                'dados' => $dados,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->treinoModel->cadastrar($dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Treino cadastrado com sucesso!');
                $this->redirect('/treinos');
                return;
            }
            $this->view('Treinos/create', [
                'alunos' => $this->alunoModel->listarTodos(),
                'professores' => $this->professorModel->listarTodos(),
                'dados' => $dados,
                'erros' => ['Não foi possível salvar o treino.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Treinos/create', [
                'alunos' => $this->alunoModel->listarTodos(),
                'professores' => $this->professorModel->listarTodos(),
                'dados' => $dados,
                'erros' => ['Erro ao cadastrar: ' . $e->getMessage()]
            ]);
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do treino inválido.');
            $this->redirect('/treinos');
            return;
        }

        $treino = $this->treinoModel->buscaPorId($id);
        if (!$treino) {
            $this->setFlash('erro', 'Treino não encontrado.');
            $this->redirect('/treinos');
            return;
        }

        $alunos = $this->alunoModel->listarTodos();
        $professores = $this->professorModel->listarTodos();

        $this->view('Treinos/edit', [
            'treino' => $treino,
            'alunos' => $alunos,
            'professores' => $professores,
            'erros' => []
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do treino inválido.');
            $this->redirect('/treinos');
            return;
        }

        $treinoAtual = $this->treinoModel->buscaPorId($id);
        if (!$treinoAtual) {
            $this->setFlash('erro', 'Treino não encontrado.');
            $this->redirect('/treinos');
            return;
        }

        $dados = [
            'aluno_id' => (int) ($_POST['aluno_id'] ?? 0),
            'professor_id' => (int) ($_POST['professor_id'] ?? 0),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'dia_semana' => trim($_POST['dia_semana'] ?? '')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $alunos = $this->alunoModel->listarTodos();
            $professores = $this->professorModel->listarTodos();
            $this->view('Treinos/edit', [
                'treino' => array_merge(['id' => $id], $dados),
                'alunos' => $alunos,
                'professores' => $professores,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->treinoModel->editar($id, $dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Treino atualizado com sucesso!');
                $this->redirect('/treinos');
                return;
            }
            $this->view('Treinos/edit', [
                'treino' => array_merge(['id' => $id], $dados),
                'alunos' => $this->alunoModel->listarTodos(),
                'professores' => $this->professorModel->listarTodos(),
                'erros' => ['Não foi possível atualizar.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Treinos/edit', [
                'treino' => array_merge(['id' => $id], $dados),
                'alunos' => $this->alunoModel->listarTodos(),
                'professores' => $this->professorModel->listarTodos(),
                'erros' => ['Erro ao atualizar: ' . $e->getMessage()]
            ]);
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do treino inválido.');
            $this->redirect('/treinos');
            return;
        }

        try {
            $resultado = $this->treinoModel->excluir($id);
            if ($resultado) {
                $this->setFlash('sucesso', 'Treino excluído com sucesso!');
            } else {
                $this->setFlash('erro', 'Não foi possível excluir.');
            }
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir: ' . $e->getMessage());
        }

        $this->redirect('/treinos');
    }

    private function validarDados(array $dados): array
    {
        $erros = [];
        if ($dados['aluno_id'] <= 0) {
            $erros[] = 'Selecione um Aluno.';
        }
        if ($dados['professor_id'] <= 0) {
            $erros[] = 'Selecione um Professor.';
        }
        if (empty($dados['descricao'])) {
            $erros[] = 'O campo Descrição do treino é obrigatório.';
        }
        if (empty($dados['dia_semana'])) {
            $erros[] = 'O campo Dia da semana é obrigatório.';
        }
        return $erros;
    }
}
