<?php

declare(strict_types=1);

$router->get('/login', LoginController::class, 'index');
$router->post('/login', LoginController::class, 'autenticar');
$router->get('/logout', LoginController::class, 'logout');

$router->get('/', DashboardController::class, 'index', [AuthMiddleware::class]);

$router->get('/perfil', UsuarioController::class, 'perfil', [AuthMiddleware::class]);
$router->post('/perfil/update', UsuarioController::class, 'atualizarPerfil', [AuthMiddleware::class]);

$router->get('/alunos', AlunoController::class, 'index', [AuthMiddleware::class]);
$router->get('/alunos/create', AlunoController::class, 'create', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/alunos/store', AlunoController::class, 'store', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->get('/alunos/edit', AlunoController::class, 'edit', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/alunos/update', AlunoController::class, 'update', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->get('/alunos/delete', AlunoController::class, 'delete', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/alunos/delete', AlunoController::class, 'delete', [AuthMiddleware::class, RecepcionistaMiddleware::class]);

$router->get('/professores', ProfessorController::class, 'index', [AuthMiddleware::class]);
$router->get('/professores/create', ProfessorController::class, 'create', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/professores/store', ProfessorController::class, 'store', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/professores/edit', ProfessorController::class, 'edit', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/professores/update', ProfessorController::class, 'update', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/professores/delete', ProfessorController::class, 'delete', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/professores/delete', ProfessorController::class, 'delete', [AuthMiddleware::class, AdminMiddleware::class]);

$router->get('/planos', PlanoController::class, 'index', [AuthMiddleware::class]);
$router->get('/planos/create', PlanoController::class, 'create', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/planos/store', PlanoController::class, 'store', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->get('/planos/edit', PlanoController::class, 'edit', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/planos/update', PlanoController::class, 'update', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->get('/planos/delete', PlanoController::class, 'delete', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/planos/delete', PlanoController::class, 'delete', [AuthMiddleware::class, RecepcionistaMiddleware::class]);

$router->get('/treinos', TreinoController::class, 'index', [AuthMiddleware::class]);
$router->get('/treinos/create', TreinoController::class, 'create', [AuthMiddleware::class, ProfessorMiddleware::class]);
$router->post('/treinos/store', TreinoController::class, 'store', [AuthMiddleware::class, ProfessorMiddleware::class]);
$router->get('/treinos/edit', TreinoController::class, 'edit', [AuthMiddleware::class, ProfessorMiddleware::class]);
$router->post('/treinos/update', TreinoController::class, 'update', [AuthMiddleware::class, ProfessorMiddleware::class]);
$router->get('/treinos/delete', TreinoController::class, 'delete', [AuthMiddleware::class, ProfessorMiddleware::class]);
$router->post('/treinos/delete', TreinoController::class, 'delete', [AuthMiddleware::class, ProfessorMiddleware::class]);

$router->get('/matriculas', MatriculaController::class, 'index', [AuthMiddleware::class]);
$router->get('/matriculas/create', MatriculaController::class, 'create', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/matriculas/store', MatriculaController::class, 'store', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->get('/matriculas/edit', MatriculaController::class, 'edit', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/matriculas/update', MatriculaController::class, 'update', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->get('/matriculas/delete', MatriculaController::class, 'delete', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/matriculas/delete', MatriculaController::class, 'delete', [AuthMiddleware::class, RecepcionistaMiddleware::class]);

$router->get('/pagamentos', PagamentoController::class, 'index', [AuthMiddleware::class]);
$router->get('/pagamentos/create', PagamentoController::class, 'create', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/pagamentos/store', PagamentoController::class, 'store', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->get('/pagamentos/edit', PagamentoController::class, 'edit', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/pagamentos/update', PagamentoController::class, 'update', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->get('/pagamentos/delete', PagamentoController::class, 'delete', [AuthMiddleware::class, RecepcionistaMiddleware::class]);
$router->post('/pagamentos/delete', PagamentoController::class, 'delete', [AuthMiddleware::class, RecepcionistaMiddleware::class]);

$router->get('/usuarios', UsuarioController::class, 'index', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/usuarios/create', UsuarioController::class, 'create', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/usuarios/store', UsuarioController::class, 'store', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/usuarios/edit', UsuarioController::class, 'edit', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/usuarios/update', UsuarioController::class, 'update', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/usuarios/delete', UsuarioController::class, 'delete', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/usuarios/delete', UsuarioController::class, 'delete', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/usuarios/toggle', UsuarioController::class, 'toggleStatus', [AuthMiddleware::class, AdminMiddleware::class]);

$router->get('/configuracoes', ConfiguracaoController::class, 'index', [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/configuracoes/salvar', ConfiguracaoController::class, 'salvar', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/relatorios', RelatorioController::class, 'index', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/relatorios/exportar-excel', RelatorioController::class, 'exportarExcel', [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/relatorios/imprimir', RelatorioController::class, 'imprimir', [AuthMiddleware::class, AdminMiddleware::class]);
