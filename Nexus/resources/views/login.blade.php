{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')              

{{-- Título de la pestaña del navegador --}}
@section('title', 'Login - Nexus')



@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#F2F2F2] px-4">
    <div class="w-full max-w-sm"> 

        <!-- ==================== LOGO ==================== -->
        <div class="text-center mb-8">
            <img src="{{ asset('img/logo-nexus.png') }}" 
                 alt="Logo" 
                 class="h-12 mx-auto">
        </div>

        <!-- ==================== TARJETA LOGIN ==================== -->
        <div class="bg-white p-6 rounded-2xl shadow-xl">
            <h1 class="text-2xl font-bold text-center text-[#2128A5] mb-6">
                Inicio de Sesión
            </h1>

            <form method="POST" action="/login" class="space-y-4">
                @csrf

                <!-- ==================== CAMPO CORREO ==================== -->
                <div>
                    <label class="block text-[#7072BF] font-semibold mb-1 text-sm">
                        Correo electrónico * 
                    </label>
                    <input type="email" 
                           name="email" 
                           required 
                           placeholder="emeraldin2025@gmail.com"
                           class="w-full px-4 py-2 border border-[#2128A5] rounded-lg bg-[#f2f2f2] focus:ring-2 focus:ring-blue-400 text-sm">
                           
                </div>

                <!-- ==================== CAMPO CONTRASEÑA/ OJITO ==================== -->
                <div class="relative">
                    <label class="block text-[#7072BF] font-semibold mb-1 text-sm">
                        Password *
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-4 py-2.5 border border-[#2128A5] rounded-lg pr-12 bg-[#f2f2f2] focus:ring-2 focus:ring-blue-400 text-sm">
                    
                    <!-- OJITO -->
                    <button type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-9 text-gray-600 hover:text-gray-800">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <!-- ==================== OLVIDÉ CONTRASEÑA ==================== -->
                <div class="text-left">
                    <a href="#" class="text-xs text-[#7072BF] hover:underline">
                        ¿Olvidé mi contraseña?
                    </a>
                </div>

                <!-- ==================== BOTÓN ==================== -->
                <button type="submit" 
                        class="w-full bg-[#2128A5] hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                    Iniciar sesión          
                </button>

                <!-- ==================== CREAR CUENTA ==================== -->
                <p class="text-left text-xs text-[#7072BF] mt-4">
                    ¿Primera vez en Nexus? 
                    <a href="#" class="underline">Crear cuenta</a>
                </p>
            </form>
        </div>

        <!-- ==================== TEXTO ABAJo ==================== -->
        <p class="text-left text-xs text-[#7072BF] mt-5">
            Powered by Nexus © 2025  
        </p>
    </div>
</div>
@endsection