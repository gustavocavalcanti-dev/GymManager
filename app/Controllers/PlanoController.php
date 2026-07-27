<?php

declare(strict_types=1);





class PlanoController extends Controller
{
    public function index(): void
    {
        $this->view('planos/index');
    }
}
