<?php

declare(strict_types=1);

$basePath = defined('BASE_PATH') ? BASE_PATH : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos - GymManager</title>
</head>

<body>
    <h1>Alunos</h1>

    <p><a href="<?= $basePath ?>/alunos/create">Cadastrar novo aluno</a></p>

    <?php if (empty($alunos)): ?>
        <p>Nenhum aluno cadastrado.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$aluno['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($aluno['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($aluno['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($aluno['telefone'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="<?= $basePath ?>/alunos/edit?id=<?= $aluno['id'] ?>">Editar</a>
                            |
                            <a href="<?= $basePath ?>/alunos/delete?id=<?= $aluno['id'] ?>" onclick="return confirm('Excluir este aluno?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>
