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
        $this->view('Alunos/create', [
            'dados' => [
                'nome' => '',
                'email' => '',
                'telefone' => ''
            ],
            'erros' => []
        ]);
    }

    public function store(): void
    {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? '')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $this->view('Alunos/create', [
                'dados' => $dados,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->alunoModel->cadastrar($dados);

            if ($resultado) {
                $this->setFlash('sucesso', 'Aluno cadastrado com sucesso!');
                $this->redirect('/alunos');
                return;
            }

            $this->view('Alunos/create', [
                'dados' => $dados,
                'erros' => ['Não foi possível salvar o aluno no banco de dados.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Alunos/create', [
                'dados' => $dados,
                'erros' => ['Erro ao cadastrar aluno: ' . $e->getMessage()]
            ]);
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('erro', 'Código do aluno inválido.');
            $this->redirect('/alunos');
            return;
        }

        $aluno = $this->alunoModel->buscaPorId($id);

        if (!$aluno) {
            $this->setFlash('erro', 'Aluno não encontrado.');
            $this->redirect('/alunos');
            return;
        }

        $this->view('Alunos/edit', [
            'aluno' => $aluno,
            'erros' => []
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('erro', 'Código do aluno inválido para atualização.');
            $this->redirect('/alunos');
            return;
        }

        $alunoAtual = $this->alunoModel->buscaPorId($id);
        if (!$alunoAtual) {
            $this->setFlash('erro', 'Aluno não encontrado para atualização.');
            $this->redirect('/alunos');
            return;
        }

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? '')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $this->view('Alunos/edit', [
                'aluno' => array_merge(['id' => $id], $dados),
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->alunoModel->editar($id, $dados);

            if ($resultado) {
                $this->setFlash('sucesso', 'Aluno atualizado com sucesso!');
                $this->redirect('/alunos');
                return;
            }

            $this->view('Alunos/edit', [
                'aluno' => array_merge(['id' => $id], $dados),
                'erros' => ['Não foi possível atualizar o aluno.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Alunos/edit', [
                'aluno' => array_merge(['id' => $id], $dados),
                'erros' => ['Erro ao atualizar aluno: ' . $e->getMessage()]
            ]);
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('erro', 'Código de aluno inválido para exclusão.');
            $this->redirect('/alunos');
            return;
        }

        $aluno = $this->alunoModel->buscaPorId($id);
        if (!$aluno) {
            $this->setFlash('erro', 'Aluno não encontrado para exclusão.');
            $this->redirect('/alunos');
            return;
        }

        try {
            $resultado = $this->alunoModel->excluir($id);

            if ($resultado) {
                $this->setFlash('sucesso', 'Aluno excluído com sucesso!');
            } else {
                $this->setFlash('erro', 'Não foi possível excluir o aluno.');
            }
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir aluno: ' . $e->getMessage());
        }

        $this->redirect('/alunos');
    }

    private function validarDados(array $dados): array
    {
        $erros = [];

        if (empty($dados['nome'])) {
            $erros[] = 'O campo Nome é obrigatório.';
        } elseif (strlen($dados['nome']) < 3) {
            $erros[] = 'O Nome deve conter pelo menos 3 caracteres.';
        } elseif (strlen($dados['nome']) > 100) {
            $erros[] = 'O Nome não pode ultrapassar 100 caracteres.';
        }

        if (empty($dados['email'])) {
            $erros[] = 'O campo E-mail é obrigatório.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um endereço de e-mail válido.';
        } elseif (strlen($dados['email']) > 150) {
            $erros[] = 'O E-mail não pode ultrapassar 150 caracteres.';
        }

        if (!empty($dados['telefone'])) {
            $apenasNumeros = preg_replace('/\D/', '', $dados['telefone']);
            if (strlen($apenasNumeros) < 8 || strlen($apenasNumeros) > 15) {
                $erros[] = 'O Telefone deve conter entre 8 e 15 dígitos numéricos.';
            }
        }

        return $erros;
    }
}
