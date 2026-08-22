<?php

declare(strict_types=1);




class PagamentoController extends Controller
{
    protected PagamentoModel $pagamentoModel;
    protected MatriculaModel $matriculaModel;

    public function __construct()
    {
        $this->pagamentoModel = new PagamentoModel();
        $this->matriculaModel = new MatriculaModel();
    }

    public function index(): void
    {
        $pagamentos = $this->pagamentoModel->listarComDetalhes();
        $this->view('Pagamentos/index', [
            'pagamentos' => $pagamentos
        ]);
    }

    public function create(): void
    {
        $matriculas = $this->matriculaModel->listarComDetalhes();
        $this->view('Pagamentos/create', [
            'matriculas' => $matriculas,
            'dados' => [
                'matricula_id' => '',
                'valor' => '',
                'data_pagamento' => date('Y-m-d'),
                'forma_pagamento' => 'pix',
                'status' => 'pago'
            ],
            'erros' => []
        ]);
    }

    public function store(): void
    {
        $dados = [
            'matricula_id' => (int) ($_POST['matricula_id'] ?? 0),
            'valor' => (float) ($_POST['valor'] ?? 0.00),
            'data_pagamento' => trim($_POST['data_pagamento'] ?? ''),
            'forma_pagamento' => trim($_POST['forma_pagamento'] ?? 'pix'),
            'status' => trim($_POST['status'] ?? 'pago')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $matriculas = $this->matriculaModel->listarComDetalhes();
            $this->view('Pagamentos/create', [
                'matriculas' => $matriculas,
                'dados' => $dados,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->pagamentoModel->cadastrar($dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Pagamento registrado com sucesso!');
                $this->redirect('/pagamentos');
                return;
            }
            $this->view('Pagamentos/create', [
                'matriculas' => $this->matriculaModel->listarComDetalhes(),
                'dados' => $dados,
                'erros' => ['Não foi possível salvar o pagamento.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Pagamentos/create', [
                'matriculas' => $this->matriculaModel->listarComDetalhes(),
                'dados' => $dados,
                'erros' => ['Erro ao cadastrar: ' . $e->getMessage()]
            ]);
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do pagamento inválido.');
            $this->redirect('/pagamentos');
            return;
        }

        $pagamento = $this->pagamentoModel->buscaPorId($id);
        if (!$pagamento) {
            $this->setFlash('erro', 'Pagamento não encontrado.');
            $this->redirect('/pagamentos');
            return;
        }

        $matriculas = $this->matriculaModel->listarComDetalhes();

        $this->view('Pagamentos/edit', [
            'pagamento' => $pagamento,
            'matriculas' => $matriculas,
            'erros' => []
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do pagamento inválido.');
            $this->redirect('/pagamentos');
            return;
        }

        $pagamentoAtual = $this->pagamentoModel->buscaPorId($id);
        if (!$pagamentoAtual) {
            $this->setFlash('erro', 'Pagamento não encontrado.');
            $this->redirect('/pagamentos');
            return;
        }

        $dados = [
            'matricula_id' => (int) ($_POST['matricula_id'] ?? 0),
            'valor' => (float) ($_POST['valor'] ?? 0.00),
            'data_pagamento' => trim($_POST['data_pagamento'] ?? ''),
            'forma_pagamento' => trim($_POST['forma_pagamento'] ?? 'pix'),
            'status' => trim($_POST['status'] ?? 'pago')
        ];

        $erros = $this->validarDados($dados);

        if (!empty($erros)) {
            $matriculas = $this->matriculaModel->listarComDetalhes();
            $this->view('Pagamentos/edit', [
                'pagamento' => array_merge(['id' => $id], $dados),
                'matriculas' => $matriculas,
                'erros' => $erros
            ]);
            return;
        }

        try {
            $resultado = $this->pagamentoModel->editar($id, $dados);
            if ($resultado) {
                $this->setFlash('sucesso', 'Pagamento atualizado com sucesso!');
                $this->redirect('/pagamentos');
                return;
            }
            $this->view('Pagamentos/edit', [
                'pagamento' => array_merge(['id' => $id], $dados),
                'matriculas' => $this->matriculaModel->listarComDetalhes(),
                'erros' => ['Não foi possível atualizar.']
            ]);
        } catch (\Throwable $e) {
            $this->view('Pagamentos/edit', [
                'pagamento' => array_merge(['id' => $id], $dados),
                'matriculas' => $this->matriculaModel->listarComDetalhes(),
                'erros' => ['Erro ao atualizar: ' . $e->getMessage()]
            ]);
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('erro', 'Código do pagamento inválido.');
            $this->redirect('/pagamentos');
            return;
        }

        try {
            $resultado = $this->pagamentoModel->excluir($id);
            if ($resultado) {
                $this->setFlash('sucesso', 'Pagamento excluído com sucesso!');
            } else {
                $this->setFlash('erro', 'Não foi possível excluir.');
            }
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir: ' . $e->getMessage());
        }

        $this->redirect('/pagamentos');
    }

    private function validarDados(array $dados): array
    {
        $erros = [];
        if ($dados['matricula_id'] <= 0) {
            $erros[] = 'Selecione uma Matrícula.';
        }
        if ($dados['valor'] <= 0) {
            $erros[] = 'O valor deve ser maior que zero.';
        }
        if (empty($dados['data_pagamento'])) {
            $erros[] = 'Data do pagamento é obrigatória.';
        }
        return $erros;
    }
}
