<?php

declare(strict_types=1);

$basePath = defined('BASE_PATH') ? BASE_PATH : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Aluno - GymManager</title>
</head>

<body>
    <h1>Cadastrar Aluno</h1>

    <form action="<?= $basePath ?>/alunos/store" method="post">
        <div>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone">
        </div>
        <div>
            <button type="submit">Salvar</button>
            <a href="<?= $basePath ?>/alunos">Cancelar</a>
        </div>
    </form>
</body>

</html>
