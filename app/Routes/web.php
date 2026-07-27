<?php

declare(strict_types=1);












// Dashboard
$router->get('/', DashboardController::class, 'index');

// Login
$router->get('/login', LoginController::class, 'index');

// Alunos
$router->get('/alunos', AlunoController::class, 'index');

// Professores
$router->get('/professores', ProfessorController::class, 'index');

// Planos
$router->get('/planos', PlanoController::class, 'index');

// Treinos
$router->get('/treinos', TreinoController::class, 'index');

// Pagamentos
$router->get('/pagamentos', PagamentoController::class, 'index');

// Matrículas
$router->get('/matriculas', MatriculaController::class, 'index');

// Usuários
$router->get('/usuarios', UsuarioController::class, 'index');

// Configurações
$router->get('/configuracoes', ConfiguracaoController::class, 'index');
