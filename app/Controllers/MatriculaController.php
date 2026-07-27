<?php

declare(strict_types=1);





class MatriculaController extends Controller
{
    public function index(): void
    {
        $this->view('matriculas/index');
    }
}
