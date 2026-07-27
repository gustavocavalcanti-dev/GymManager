<?php

declare(strict_types=1);





class ConfiguracaoController extends Controller
{
    public function index(): void
    {
        $this->view('configuracoes/index');
    }
}
