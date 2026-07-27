<?php

declare(strict_types=1);





class AlunoController extends Controller
{
    public function index(): void
    {
        $this->view('alunos/index');
    }
}
