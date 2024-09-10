<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function index()
    {
        // Recopila datos necesarios para el dashboard
        return view('reportes.index');
    }
}
