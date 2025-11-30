<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Muestra la página de inicio.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Aquí se cargarán los datos para la página de inicio
        return view('client.home');
    }
}
