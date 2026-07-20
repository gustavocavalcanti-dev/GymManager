<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class RelatorioController extends Controller
{
    public function index(): void
    {
        $this->view('relatorios/index');
    }
}