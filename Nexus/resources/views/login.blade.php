{{-- Esto dice: usa el marco que está en layouts/app.blade.php --}}
@extends('layouts.app')

{{-- Título de la pestaña del navegador --}}
@section('title', 'Login - Nexus')

{{-- Aquí va SOLO el contenido del login (sin <html>, <head>, <body>) --}}
@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#F2F2F2] py-12 px-4">
    <div class="flex flex-col items-center space-y-6 w-full max-w-md">

        <!-- LOGO -->
        <div class="mb-5">
            <img src="{{ asset('img/logo-nexus.png') }}" alt="Logo" class="h-12 mx-auto">
        </div>

        <!-- TARJETA LOGIN -->
        <div class="bg-white p-10 rounded-2xl shadow-2xl w-full">
            <h1 class="text-3xl font-bold text-center text-[#2128A5] mb-8">
                Inicio de Sesión
            </h1>

            <form method="POST" action="/login" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-[#7072BF] font-semibold mb-1">Correo electrónico *</label>
                    <input type="email" name="email" required placeholder="tuemail@gmail.com"
                           class="w-full px-4 py-3 border border-[#2128A5] rounded-lg focus:ring-4 focus:ring-blue-400 bg-[#f2f2f2]">
                </div>

                <div class="relative">
                    <label class="block text-[#7072BF] font-semibold mb-1">Contraseña *</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-[#2128A5] rounded-lg pr-12 bg-[#f2f2f2] focus:ring-4 focus:ring-blue-400">
                    <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-11 text-gray-600 hover:text-gray-800">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <div class="text-left">
                    <a href="#" class="text-sm text-[#7072BF] hover:underline">¿Olvidé mi contraseña?</a>
                </div>

                <button type="submit" 
                        class="w-full bg-[#2128A5] hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    Iniciar sesión
                </button>

                <p class="text-center text-sm text-[#7072BF] mt-6">
                    ¿Primera vez en Nexus? <a href="#" class="underline">Crear cuenta</a>
                </p>
            </div>

        <p class="text-sm text-[#7072BF]">Powered by Nexus © 2025</p>
    </div>
</div>
@endsection