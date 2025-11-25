@extends('layouts.app')

@section('content')
<div class="bg-[#f4f6fb]">

    <!-- HERO -->
    <section class="relative w-full bg-cover bg-center" 
             style="background-image: url('/img/fondo-nexus.jpg');">

        <!-- Capa oscura -->
        <div class="absolute inset-0 bg-black/10"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-24 grid grid-cols-1 md:grid-cols-2 gap-12">

            <!-- Texto -->
            <div class="flex flex-col justify-center">
                <h2 class="text-5xl font-bold text-white drop-shadow-lg leading-tight">
                    Encuentra lo <br> que buscas
                </h2>

                <p class="text-white/90 mt-4 text-lg drop-shadow">
                    Productos seleccionados<br> para tu estilo de vida.
                </p>

                <button class="mt-6 bg-teal-400 hover:bg-teal-500 transition text-white font-semibold px-6 py-3 rounded-full w-fit shadow-md">
                    Comprar ahora
                </button>
            </div>

        </div>
    </section>

    <!-- MARCAS -->
    <section class="max-w-7xl mx-auto px-6 py-10 flex flex-wrap justify-between items-center opacity-90">
        <img src="/img/jbl.png" class="h-10" alt="">
        <img src="/img/boss.png" class="h-10" alt="">
        <img src="/img/lenovo.png" class="h-10" alt="">
        <img src="/img/zara.png" class="h-10" alt="">
        <img src="/img/philips.png" class="h-10" alt="">
    </section>

    <!-- PRODUCTOS NUEVOS -->
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-6">

            <h3 class="text-2xl font-bold text-gray-800 mb-6">Productos nuevos</h3>

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                <!-- Card 1 -->
                <div class="bg-white shadow-md rounded-xl p-4">
                    <img src="/img/tv-retro.png" class="rounded-lg mb-3">
                    <h4 class="font-semibold text-gray-800">Televisión Retro</h4>
                    <p class="text-sm text-gray-500">Adorno retro inspirado en televisores antiguos.</p>
                    <p class="font-bold text-indigo-600 mt-2">Q 500.00</p>
                    <button class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-md w-full">
                        Añadir al carrito
                    </button>
                </div>

                <!-- Card 2 -->
                <div class="bg-white shadow-md rounded-xl p-4">
                    <img src="/img/especieros.png" class="rounded-lg mb-3">
                    <h4 class="font-semibold text-gray-800">Especieros</h4>
                    <p class="text-sm text-gray-500">Juego de contenedores elegantes para cocina.</p>
                    <p class="font-bold text-indigo-600 mt-2">Q 350.00</p>
                    <button class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-md w-full">
                        Añadir al carrito
                    </button>
                </div>

                <!-- Card 3 -->
                <div class="bg-white shadow-md rounded-xl p-4">
                    <img src="/img/guantes.png" class="rounded-lg mb-3">
                    <h4 class="font-semibold text-gray-800">Guantes</h4>
                    <p class="text-sm text-gray-500">Guantes deportivos de alta resistencia.</p>
                    <p class="font-bold text-indigo-600 mt-2">Q 120.00</p>
                    <button class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-md w-full">
                        Añadir al carrito
                    </button>
                </div>

                <!-- Card 4 -->
                <div class="bg-white shadow-md rounded-xl p-4">
                    <img src="/img/tiro-arco.png" class="rounded-lg mb-3">
                    <h4 class="font-semibold text-gray-800">Tiro al arco</h4>
                    <p class="text-sm text-gray-500">Set de arco para práctica recreativa.</p>
                    <p class="font-bold text-indigo-600 mt-2">Q 750.00</p>
                    <button class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-md w-full">
                        Añadir al carrito
                    </button>
                </div>

            </div>

            <div class="text-center mt-10">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-8 py-3 rounded-full">
                    Ver todo lo nuevo
                </button>
            </div>

        </div>
    </section>

</div>
@endsection