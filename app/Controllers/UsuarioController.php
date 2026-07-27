<?php

declare(strict_types=1);





class UsuarioController extends Controller
{
    public function index(): void
    {
        $this->view('usuarios/index');
    }
}
