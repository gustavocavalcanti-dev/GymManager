<?php

declare(strict_types=1);




class ProfessorController extends Controller
{
    protected ProfessorModel $professorModel;

    public function __construct()
    {
        $this->professorModel = new ProfessorModel();
    }

    public function index(): void
    {
        $professores = $this->professorModel->listarTodos();
        $this->view('Professores/index', [
            'professores' => $professores
        ]);
    }

    public function create(): void
    {
        $this->view('Professores/create', [
            'dados' => [
                'nome' => '',
                'email' => '',
                'telefone' => '',
                'especialidade' => ''
            ],
            'erros' => []
        ]);
    }

    public function store(): void
    {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'especialidade' => trim($_POST['especialidade'] ?? '')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $this->view('Professores/create', [
                'dados' => $dados,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->professorModel->cadastrar($dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Professor cadastrado com sucesso!');
                $this->redirect('/professores');
                return;
            }
            $this->view('Professores/create', [
                'dados' => $dados,
                'erros' => ['Não foi possível salvar o professor.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Professores/create', [
                'dados' => $dados,
                'erros' => ['Erro ao cadastrar: ' . $e->getMessage()]
            ]);
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do professor inválido.');
            $this->redirect('/professores');
            return;
        }

        $professor = $this->professorModel->buscaPorId($id);
        if (!$professor) {
            $this->setFlash('erro', 'Professor não encontrado.');
            $this->redirect('/professores');
            return;
        }

        $this->view('Professores/edit', [
            'professor' => $professor,
            'erros' => []
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do professor inválido.');
            $this->redirect('/professores');
            return;
        }

        $professorAtual = $this->professorModel->buscaPorId($id);
        if (!$professorAtual) {
            $this->setFlash('erro', 'Professor não encontrado.');
            $this->redirect('/professores');
            return;
        }

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'especialidade' => trim($_POST['especialidade'] ?? '')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $this->view('Professores/edit', [
                'professor' => array_merge(['id' => $id], $dados),
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->professorModel->editar($id, $dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Professor atualizado com sucesso!');
                $this->redirect('/professores');
                return;
            }
            $this->view('Professores/edit', [
                'professor' => array_merge(['id' => $id], $dados),
                'erros' => ['Não foi possível atualizar.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Professores/edit', [
                'professor' => array_merge(['id' => $id], $dados),
                'erros' => ['Erro ao atualizar: ' . $e->getMessage()]
            ]);
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do professor inválido.');
            $this->redirect('/professores');
            return;
        }

        try {
            $resultado = $this->professorModel->excluir($id);
            if ($resultado) {
                $this->setFlash('sucesso', 'Professor excluído com sucesso!');
            } else {
                $this->setFlash('erro', 'Não foi possível excluir.');
            }
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir: ' . $e->getMessage());
        }

        $this->redirect('/professores');
    }

    private function validarDados(array $dados): array
    {
        $erros = [];
        if (empty($dados['nome'])) {
            $erros[] = 'O campo Nome é obrigatório.';
        }
        if (empty($dados['email'])) {
            $erros[] = 'O campo E-mail é obrigatório.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'E-mail inválido.';
        }
        return $erros;
    }
}
