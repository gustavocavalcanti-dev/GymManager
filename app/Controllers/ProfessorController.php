<?php

declare(strict_types=1);





class ProfessorController extends Controller
{
    public function index(): void
    {
        $this->view('professores/index');
    }
}
