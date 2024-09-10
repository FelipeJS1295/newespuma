<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirigir a los usuarios después del login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Crear una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        // No se usa middleware aquí.
    }

    // Puedes definir otros métodos de autenticación o personalizar el comportamiento del login aquí
}

