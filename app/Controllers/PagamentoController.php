<?php

declare(strict_types=1);

use App\Models\Matricula;
use App\Models\Pagamento;

class PagamentoController extends Controller
{
    private Pagamento $model;
    private Matricula $matriculaModel;

    public function __construct()
    {
        $this->model = new Pagamento();
        $this->matriculaModel = new Matricula();
    }

    public function index(): void
    {
        $this->view('Pagamentos/index', ['pagamentos' => $this->model->listarComDetalhes()]);
    }

    public function create(): void
    {
        $this->view('Pagamentos/create', [
            'dados' => [
                'matricula_id' => '', 'valor' => '', 'data_vencimento' => date('Y-m-d'),
                'data_pagamento' => '', 'forma_pagamento' => '', 'status' => 'pendente',
            ],
            'matriculas' => $this->matriculaModel->listarComDetalhes(),
            'erros' => [],
        ]);
    }

    public function store(): void
    {
        $this->validarCSRF('/pagamentos');
        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($erros !== []) {
            $this->renderFormulario('Pagamentos/create', $dados, $erros);
            return;
        }

        try {
            $this->model->cadastrar($dados);
            $this->model->atualizarAtrasados();
            $this->setFlash('sucesso', 'Pagamento registrado com sucesso!');
            $this->redirect('/pagamentos');
        } catch (\Throwable $e) {
            $this->renderFormulario('Pagamentos/create', $dados, ['Erro ao registrar pagamento: ' . $e->getMessage()]);
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $pagamento = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$pagamento) {
            $this->setFlash('erro', 'Pagamento não encontrado.');
            $this->redirect('/pagamentos');
        }

        $this->view('Pagamentos/edit', [
            'pagamento' => $pagamento,
            'matriculas' => $this->matriculaModel->listarComDetalhes(),
            'erros' => [],
        ]);
    }

    public function update(): void
    {
        $this->validarCSRF('/pagamentos');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Pagamento não encontrado.');
            $this->redirect('/pagamentos');
        }

        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($erros !== []) {
            $this->view('Pagamentos/edit', [
                'pagamento' => array_merge(['id' => $id], $dados),
                'matriculas' => $this->matriculaModel->listarComDetalhes(),
                'erros' => $erros,
            ]);
            return;
        }

        try {
            $this->model->atualizar($id, $dados);
            $this->model->atualizarAtrasados();
            $this->setFlash('sucesso', 'Pagamento atualizado com sucesso!');
            $this->redirect('/pagamentos');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao atualizar pagamento: ' . $e->getMessage());
            $this->redirect('/pagamentos');
        }
    }

    public function delete(): void
    {
        $this->validarCSRF('/pagamentos');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Pagamento não encontrado.');
            $this->redirect('/pagamentos');
        }

        try {
            $this->model->excluir($id);
            $this->setFlash('sucesso', 'Pagamento excluído com sucesso!');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Erro ao excluir pagamento: ' . $e->getMessage());
        }

        $this->redirect('/pagamentos');
    }

    private function dadosFormulario(): array
    {
        $valor = str_replace(',', '.', trim((string)($_POST['valor'] ?? '0')));
        $status = (string)($_POST['status'] ?? 'pendente');
        $dataPagamento = trim((string)($_POST['data_pagamento'] ?? ''));
        $forma = (string)($_POST['forma_pagamento'] ?? '');

        if ($status === 'pago' && $dataPagamento === '') {
            $dataPagamento = date('Y-m-d');
        }

        return [
            'matricula_id' => (int)($_POST['matricula_id'] ?? 0),
            'valor' => (float)$valor,
            'data_vencimento' => (string)($_POST['data_vencimento'] ?? ''),
            'data_pagamento' => $dataPagamento !== '' ? $dataPagamento : null,
            'forma_pagamento' => in_array($forma, ['dinheiro', 'pix', 'cartao', 'boleto'], true) ? $forma : null,
            'status' => in_array($status, ['pendente', 'pago', 'atrasado', 'cancelado'], true) ? $status : 'pendente',
        ];
    }

    private function validarDados(array $dados): array
    {
        $erros = [];

        if ($dados['matricula_id'] <= 0 || !$this->matriculaModel->buscarPorId($dados['matricula_id'])) {
            $erros[] = 'Selecione uma matrícula válida.';
        }

        if ($dados['valor'] <= 0) {
            $erros[] = 'O valor deve ser maior que zero.';
        }

        if (strtotime($dados['data_vencimento']) === false) {
            $erros[] = 'Informe uma data de vencimento válida.';
        }

        if ($dados['status'] === 'pago') {
            if ($dados['data_pagamento'] === null || strtotime($dados['data_pagamento']) === false) {
                $erros[] = 'Pagamento marcado como pago precisa de uma data de pagamento.';
            }
            if ($dados['forma_pagamento'] === null) {
                $erros[] = 'Informe a forma de pagamento.';
            }
        }

        return $erros;
    }

    private function renderFormulario(string $view, array $dados, array $erros): void
    {
        $this->view($view, [
            'dados' => $dados,
            'matriculas' => $this->matriculaModel->listarComDetalhes(),
            'erros' => $erros,
        ]);
    }
}
