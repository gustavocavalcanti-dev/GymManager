<?php

declare(strict_types=1);

class AlunoController extends Controller
{
    protected AlunoModel $alunoModel;

    public function __construct()
    {
        $this->alunoModel = new AlunoModel();
    }

    public function index(): void
    {
        $alunos = $this->alunoModel->listarTodos();

        $this->view('Alunos/index', [
            'alunos' => $alunos
        ]);
    }

    public function create(): void
    {
        $this->view('Alunos/create');
    }

    public function store(): void
    {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? '')
        ];

        $this->alunoModel->cadastrar($dados);

        header('Location: ' . (defined('BASE_PATH') ? BASE_PATH : '') . '/alunos');
        exit;
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: ' . (defined('BASE_PATH') ? BASE_PATH : '') . '/alunos');
            exit;
        }

        $aluno = $this->alunoModel->buscaPorId($id);

        if (!$aluno) {
            header('Location: ' . (defined('BASE_PATH') ? BASE_PATH : '') . '/alunos');
            exit;
        }

        $this->view('Alunos/edit', [
            'aluno' => $aluno
        ]);
    }

    public function update(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: ' . (defined('BASE_PATH') ? BASE_PATH : '') . '/alunos');
            exit;
        }

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? '')
        ];

        $this->alunoModel->editar($id, $dados);

        header('Location: ' . (defined('BASE_PATH') ? BASE_PATH : '') . '/alunos');
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->alunoModel->excluir($id);
        }

        header('Location: ' . (defined('BASE_PATH') ? BASE_PATH : '') . '/alunos');
        exit;
    }
}
