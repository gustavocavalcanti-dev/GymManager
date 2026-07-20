<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class TreinoController extends Controller
{
    public function index(): void
    {
        $this->view('treinos/index');
    }
}