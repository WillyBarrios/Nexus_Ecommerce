<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function profile()
    {
        // Aquí se cargarán datos reales del usuario
        return view('account.profile');
    }

    public function updateProfile(Request $request)
    {
        // Aquí se validará y guardará en la BD.
        // Por ahora solo valida y muestra un mesnaje.

        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'birth_date'    => 'required|date',
            'phone'         => 'required|string|max:20',
            'email'         => 'required|email',
        ]);

        // Aquí:
        // auth()->user()->update([...]);

        return back()->with('status', 'Perfil actualizado (modo demo, sin guardar en BD).');
    }
    public function address()
{
    // Aquí se cargará la dirección real del usuario
    return view('account.address');
}

public function updateAddress(Request $request)
{
    $request->validate([
        'street'      => 'required|string|max:255',
        'number'      => 'nullable|string|max:50',
        'city'        => 'required|string|max:255',
        'department'  => 'required|string|max:255',
        'postal_code' => 'nullable|string|max:20',
        'reference'   => 'nullable|string|max:500',
    ]);

    // Aquí se coloca: auth()->user()->address()->updateOrCreate([...])

    return back()->with('status', 'Dirección actualizada (modo demo, sin guardar en BD).');
}
}

