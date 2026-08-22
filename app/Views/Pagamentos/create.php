<?php
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
include dirname(__DIR__) . '/layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <div>
            <h1 class="page-title">Registrar Pagamento</h1>
        </div>
    </div>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= $basePath ?>/pagamentos/store" method="post">
        <div class="form-group">
            <label for="matricula_id" class="form-label">Matrícula (Aluno - Plano)</label>
            <select name="matricula_id" id="matricula_id" class="form-control" required>
                <option value="">Selecione a Matrícula</option>
                <?php foreach ($matriculas as $mat): ?>
                    <option value="<?= $mat['id'] ?>" <?= (int)($dados['matricula_id'] ?? 0) === (int)$mat['id'] ? 'selected' : '' ?>>
                        #<?= $mat['id'] ?>: <?= htmlspecialchars($mat['aluno_nome']) ?> - <?= htmlspecialchars($mat['plano_nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="valor" class="form-label">Valor Pago (R$)</label>
            <input type="number" step="0.01" name="valor" id="valor" class="form-control" value="<?= htmlspecialchars((string)($dados['valor'] ?? '')) ?>" required>
        </div>
        <div class="form-group">
            <label for="data_pagamento" class="form-label">Data de Pagamento</label>
            <input type="date" name="data_pagamento" id="data_pagamento" class="form-control" value="<?= htmlspecialchars($dados['data_pagamento'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
            <select name="forma_pagamento" id="forma_pagamento" class="form-control">
                <option value="pix" <?= ($dados['forma_pagamento'] ?? '') === 'pix' ? 'selected' : '' ?>>PIX</option>
                <option value="dinheiro" <?= ($dados['forma_pagamento'] ?? '') === 'dinheiro' ? 'selected' : '' ?>>Dinheiro</option>
                <option value="cartao_credito" <?= ($dados['forma_pagamento'] ?? '') === 'cartao_credito' ? 'selected' : '' ?>>Cartão de Crédito</option>
                <option value="cartao_debito" <?= ($dados['forma_pagamento'] ?? '') === 'cartao_debito' ? 'selected' : '' ?>>Cartão de Débito</option>
                <option value="boleto" <?= ($dados['forma_pagamento'] ?? '') === 'boleto' ? 'selected' : '' ?>>Boleto Bancário</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="pago" <?= ($dados['status'] ?? '') === 'pago' ? 'selected' : '' ?>>Pago</option>
                <option value="pendente" <?= ($dados['status'] ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="cancelado" <?= ($dados['status'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= $basePath ?>/pagamentos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
include dirname(__DIR__) . '/layouts/footer.php';
?>
