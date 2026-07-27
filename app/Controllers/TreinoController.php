<?php

declare(strict_types=1);





class TreinoController extends Controller
{
    public function index(): void
    {
        $this->view('treinos/index');
    }
}
