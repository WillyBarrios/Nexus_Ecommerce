<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Muestra el dashboard de administración.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Aquí se agregarán los datos para el dashboard
        return view('admin.dashboard');
    }

    /**
     * Muestra la página de perfil del administrador.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        // Aquí se cargarán los datos del usuario administrador
        return view('admin.profile');
    }

    /**
     * Actualiza el perfil del administrador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        // Lógica para actualizar el perfil
        return redirect()->route('admin.profile')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Actualiza la contraseña del administrador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Lógica para actualizar la contraseña
        return redirect()->route('admin.profile')->with('success', 'Contraseña actualizada correctamente.');
    }
}
