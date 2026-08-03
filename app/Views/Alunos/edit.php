<?php

declare(strict_types=1);

$basePath = defined('BASE_PATH') ? BASE_PATH : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno - GymManager</title>
</head>

<body>
    <h1>Editar Aluno</h1>

    <?php if (!isset($aluno) || empty($aluno)): ?>
        <p>Aluno não encontrado.</p>
        <p><a href="<?= $basePath ?>/alunos">Voltar</a></p>
        <?php return; ?>
    <?php endif; ?>

    <form action="<?= $basePath ?>/alunos/update?id=<?= (int)$aluno['id'] ?>" method="post">
        <div>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($aluno['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($aluno['email'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div>
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($aluno['telefone'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div>
            <button type="submit">Atualizar</button>
            <a href="<?= $basePath ?>/alunos">Cancelar</a>
        </div>
    </form>
</body>

</html>
