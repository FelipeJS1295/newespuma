<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        // Mostrar registros de entradas y salidas
        return view('caja.index');
    }
}
