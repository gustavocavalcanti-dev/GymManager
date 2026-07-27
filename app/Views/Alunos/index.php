<?php


require_once __DIR__ . '/../../Models/AlunoModel.php';
require_once __DIR__ . '/../../Core/Model.php';
require_once __DIR__ . '/../../Core/Database.php';

$alunoModel = new AlunoModel();

$alunos = $alunoModel->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos - GymManager</title>
</head>

<body>

    <h1>Gerenciamento de Alunos</h1>

    <hr>

    <p>Bem-vindo ao módulo de alunos.</p>

    <p>Aqui será possível:</p>

    <ul>
        <li>Cadastrar alunos</li>
        <li>Editar alunos</li>
        <li>Excluir alunos</li>
        <li>Visualizar todos os alunos</li>
        <pre>
<?php
print_r($alunos);
?>
</pre>
    </ul>

</body>

</html>
