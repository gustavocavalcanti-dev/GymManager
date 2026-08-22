<?php

declare(strict_types=1);


class PlanoController extends Controller
{
    protected PlanoModel $planoModel;

    public function __construct()
    {
        $this->planoModel = new PlanoModel();
    }

    public function index(): void
    {
        $planos = $this->planoModel->listarTodos();
        $this->view('Planos/index', [
            'planos' => $planos
        ]);
    }

    public function create(): void
    {
        $this->view('Planos/create', [
            'dados' => [
                'nome' => '',
                'descricao' => '',
                'valor' => '',
                'duracao_meses' => '1'
            ],
            'erros' => []
        ]);
    }

    public function store(): void
    {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'valor' => (float) ($_POST['valor'] ?? 0.00),
            'duracao_meses' => (int) ($_POST['duracao_meses'] ?? 1)
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $this->view('Planos/create', [
                'dados' => $dados,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->planoModel->cadastrar($dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Plano cadastrado com sucesso!');
                $this->redirect('/planos');
                return;
            }
            $this->view('Planos/create', [
                'dados' => $dados,
                'erros' => ['Não foi possível salvar o plano.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Planos/create', [
                'dados' => $dados,
                'erros' => ['Erro ao cadastrar: ' . $e->getMessage()]
            ]);
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do plano inválido.');
            $this->redirect('/planos');
            return;
        }

        $plano = $this->planoModel->buscaPorId($id);
        if (!$plano) {
            $this->setFlash('erro', 'Plano não encontrado.');
            $this->redirect('/planos');
            return;
        }

        $this->view('Planos/edit', [
            'plano' => $plano,
            'erros' => []
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do plano inválido.');
            $this->redirect('/planos');
            return;
        }

        $planoAtual = $this->planoModel->buscaPorId($id);
        if (!$planoAtual) {
            $this->setFlash('erro', 'Plano não encontrado.');
            $this->redirect('/planos');
            return;
        }

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'valor' => (float) ($_POST['valor'] ?? 0.00),
            'duracao_meses' => (int) ($_POST['duracao_meses'] ?? 1)
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $this->view('Planos/edit', [
                'plano' => array_merge(['id' => $id], $dados),
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->planoModel->editar($id, $dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Plano atualizado com sucesso!');
                $this->redirect('/planos');
                return;
            }
            $this->view('Planos/edit', [
                'plano' => array_merge(['id' => $id], $dados),
                'erros' => ['Não foi possível atualizar.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Planos/edit', [
                'plano' => array_merge(['id' => $id], $dados),
                'erros' => ['Erro ao atualizar: ' . $e->getMessage()]
            ]);
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do plano inválido.');
            $this->redirect('/planos');
            return;
        }

        try {
            $resultado = $this->planoModel->excluir($id);
            if ($resultado) {
                $this->setFlash('sucesso', 'Plano excluído com sucesso!');
            } else {
                $this->setFlash('erro', 'Não foi possível excluir.');
            }
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir: ' . $e->getMessage());
        }

        $this->redirect('/planos');
    }

    private function validarDados(array $dados): array
    {
        $erros = [];
        if (empty($dados['nome'])) {
            $erros[] = 'O campo Nome é obrigatório.';
        }
        if ($dados['valor'] <= 0) {
            $erros[] = 'O valor do plano deve ser maior que zero.';
        }
        if ($dados['duracao_meses'] <= 0) {
            $erros[] = 'A duração deve ser de pelo menos 1 mês.';
        }
        return $erros;
    }
}
