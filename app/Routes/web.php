<?php

declare(strict_types=1);

$router->get('/', DashboardController::class, 'index');
$router->get('/login', LoginController::class, 'index');

$router->get('/alunos', AlunoController::class, 'index');
$router->get('/alunos/create', AlunoController::class, 'create');
$router->post('/alunos/store', AlunoController::class, 'store');
$router->get('/alunos/edit', AlunoController::class, 'edit');
$router->post('/alunos/update', AlunoController::class, 'update');
$router->get('/alunos/delete', AlunoController::class, 'delete');
$router->post('/alunos/delete', AlunoController::class, 'delete');

$router->get('/professores', ProfessorController::class, 'index');
$router->get('/planos', PlanoController::class, 'index');
$router->get('/treinos', TreinoController::class, 'index');
$router->get('/pagamentos', PagamentoController::class, 'index');
$router->get('/matriculas', MatriculaController::class, 'index');
$router->get('/usuarios', UsuarioController::class, 'index');
$router->get('/configuracoes', ConfiguracaoController::class, 'index');
