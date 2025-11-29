<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function home()
    {
        // Aquí pones la vista que quieres mostrar en la página principal
        return view('home');
    }
}
