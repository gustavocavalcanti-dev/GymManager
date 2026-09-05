<?php

declare(strict_types=1);

use App\Models\Plano;

class PlanoController extends Controller
{
    private Plano $model;

    public function __construct()
    {
        $this->model = new Plano();
    }

    public function index(): void
    {
        $this->view('Planos/index', ['planos' => $this->model->listarTodos()]);
    }

    public function create(): void
    {
        $this->view('Planos/create', [
            'dados' => [
                'nome' => '', 'descricao' => '', 'duracao_meses' => 1,
                'valor' => '0.00', 'status' => 'ativo',
            ],
            'erros' => [],
        ]);
    }

    public function store(): void
    {
        $this->validarCSRF('/planos');
        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($erros !== []) {
            $this->view('Planos/create', compact('dados', 'erros'));
            return;
        }

        try {
            $this->model->cadastrar($dados);
            $this->setFlash('sucesso', 'Plano cadastrado com sucesso!');
            $this->redirect('/planos');
        } catch (\Throwable $e) {
            $erros = ['Erro ao cadastrar plano: ' . $e->getMessage()];
            $this->view('Planos/create', compact('dados', 'erros'));
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $plano = $id > 0 ? $this->model->buscarPorId($id) : false;

        if (!$plano) {
            $this->setFlash('erro', 'Plano não encontrado.');
            $this->redirect('/planos');
        }

        $this->view('Planos/edit', ['plano' => $plano, 'erros' => []]);
    }

    public function update(): void
    {
        $this->validarCSRF('/planos');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Plano não encontrado.');
            $this->redirect('/planos');
        }

        $dados = $this->dadosFormulario();
        $erros = $this->validarDados($dados);

        if ($erros !== []) {
            $this->view('Planos/edit', [
                'plano' => array_merge(['id' => $id], $dados),
                'erros' => $erros,
            ]);
            return;
        }

        try {
            $this->model->atualizar($id, $dados);
            $this->setFlash('sucesso', 'Plano atualizado com sucesso!');
            $this->redirect('/planos');
        } catch (\Throwable $e) {
            $this->view('Planos/edit', [
                'plano' => array_merge(['id' => $id], $dados),
                'erros' => ['Erro ao atualizar plano: ' . $e->getMessage()],
            ]);
        }
    }

    public function delete(): void
    {
        $this->validarCSRF('/planos');
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0 || !$this->model->buscarPorId($id)) {
            $this->setFlash('erro', 'Plano não encontrado.');
            $this->redirect('/planos');
        }

        try {
            $this->model->excluir($id);
            $this->setFlash('sucesso', 'Plano excluído com sucesso!');
        } catch (\Throwable $e) {
            $this->setFlash('erro', 'Não foi possível excluir o plano. Verifique se existem matrículas vinculadas.');
        }

        $this->redirect('/planos');
    }

    private function dadosFormulario(): array
    {
        $valor = str_replace(',', '.', trim((string)($_POST['valor'] ?? '0')));

        return [
            'nome' => trim((string)($_POST['nome'] ?? '')),
            'descricao' => trim((string)($_POST['descricao'] ?? '')),
            'duracao_meses' => (int)($_POST['duracao_meses'] ?? 0),
            'valor' => (float)$valor,
            'status' => in_array((string)($_POST['status'] ?? ''), ['ativo', 'inativo'], true)
                ? (string)$_POST['status']
                : 'ativo',
        ];
    }

    private function validarDados(array $dados): array
    {
        $erros = [];
        if (strlen($dados['nome']) < 2) $erros[] = 'Informe o nome do plano.';
        if ($dados['duracao_meses'] <= 0) $erros[] = 'A duração deve ser maior que zero.';
        if ($dados['valor'] < 0) $erros[] = 'O valor não pode ser negativo.';
        return $erros;
    }
}
