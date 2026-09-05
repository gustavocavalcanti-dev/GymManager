<?php
declare(strict_types=1);

if (!class_exists('PagamentoModel', false)) {
    class_alias(\App\Models\Pagamento::class, 'PagamentoModel');
}
