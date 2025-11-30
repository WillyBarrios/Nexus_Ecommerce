<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

// Portada Nexus directamente en "/"
Route::get('/', function () {
    return view('home');   // usa resources/views/home.blade.php
})->name('home');

// Login aparte
Route::get('/login', function () {
    return view('login');
})->name('login');

// Login
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    // Login falso para prueba
    session(['logged_in' => true]);
    return redirect('/home');
});

//ruta para olvide mi contrasena
Route::get('/password', function () {
    return view('password');
});

//ruta para creacion de usuario
Route::get('/register', function () {
    return view('register');
});


// 3. Home (protegido)
Route::get('/home', function () {
    if (!session('logged_in')) {
        return redirect('/login');
    }
    return view('home');
})->name('home');

//. Logout
Route::post('/logout', function () {
    session()->flush();
    return redirect('/login');
});

// Página de contacto
Route::get('/contacto', function () {
    return view('contact');
})->name('contact');

//Perfil usuario
Route::get('/mi-cuenta/perfil', [AccountController::class, 'profile'])
    ->name('account.profile');

    Route::post('/mi-cuenta/perfil', [AccountController::class, 'updateProfile'])
    ->name('account.profile.update');




    // Olvidé contraseña → formulario de correo
Route::get('/password', function () {
    return view('password');
})->name('password.reset');

// Simulación: al enviar correo, redirige a cambiar contraseña
Route::post('/password', function () {
    // Aquí iría el envío real del correo, pero para prueba:
    return redirect('/reset-password')->with('status', 'Te enviamos un 
    enlace por correo');
});

// Cambiar contraseña
Route::get('/reset-password', function () {
    return view('reset-password');
});

// Guardar nueva contraseña (falso, solo para prueba)
Route::post('/reset-password', function () {
    return redirect('/login')->with('status', '¡Contraseña cambiada con éxito!');
});




// Registro - crea cuenta y LOGUEA AUTOMÁTICO
Route::post('/register', function () {
    // Aquí iría el código real de crear usuario en base de datos
    // Pero para prueba rápida (login falso):

    session(['logged_in' => true, 'nombre' => request('name')]);

    return redirect('/home')->with('status', '¡Cuenta creada con éxito! Bienvenido');
});