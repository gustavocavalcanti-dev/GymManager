<?php

declare(strict_types=1);





class AlunoController extends Controller
{
    protected AlunoModel $alunoModel;

    public function __construct()
    {
        $this->alunoModel = new AlunoModel();
    }

    public function index()
    {
        $alunos = $this->alunoModel->listarTodos();

        $this->view('aluno/index', [
            'alunos' => $alunos
        ]);
        }

  public function listarPorId(int $id): void
{
    $aluno = $this->alunoModel->buscaPorId($id);

    $this->view('alunos/detalhes', [
        'aluno' => $aluno
    ]);

    public function create(){
        $this->view('alunos/create');

    };

    public function store() :void{
 $dados = [
    'nome' => trim($_POST['nome'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'telefone' => trim($_POST['telefone'] ?? '')
];
      $this->alunoModel->cadastrar($dados);

    header('Location: /alunos');
}
public function update(int $id): void
{
    $dados = [
        'nome' => trim($_POST['nome'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'telefone' => trim($_POST['telefone'] ?? '')
    ];

    $this->alunoModel->editar($id, $dados);

    header('Location: /alunos');
}
}