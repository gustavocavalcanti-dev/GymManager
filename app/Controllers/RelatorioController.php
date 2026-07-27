<?php

declare(strict_types=1);





class RelatorioController extends Controller
{
    public function index(): void
    {
        $this->view('relatorios/index');
    }
}
