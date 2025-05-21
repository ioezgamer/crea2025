<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Asegúrate de importar View

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio general.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('home'); // Retornará la vista home.blade.php
    }
}
