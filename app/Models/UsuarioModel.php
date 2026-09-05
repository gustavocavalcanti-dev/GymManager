<?php
declare(strict_types=1);

if (!class_exists('UsuarioModel', false)) {
    class_alias(\App\Models\Usuario::class, 'UsuarioModel');
}
