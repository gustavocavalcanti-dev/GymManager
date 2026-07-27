<?php

declare(strict_types=1);





class PagamentoController extends Controller
{
    public function index(): void
    {
        $this->view('pagamentos/index');
    }
}
