<?php

use Illuminate\Support\Facades\Route;

// 1. Página principal → siempre lleva al login primero
Route::get('/', function () {
    return redirect('/login');
});

// 2. Login
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    // Login falso para prueba
    session(['logged_in' => true]);
    return redirect('/home');
});

// 3. Home / Dashboard (protegido)
Route::get('/home', function () {
    if (!session('logged_in')) {
        return redirect('/login');
    }
    return view('home'); // tu home.blade.php
})->name('home');

// 4. Logout
Route::post('/logout', function () {
    session()->flush();
    return redirect('/login');
});