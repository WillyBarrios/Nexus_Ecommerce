@extends('layouts.app')

@section('content')
<div class="bg-[#f4f6fb]">

    {{-- HERO PRINCIPAL --}}
    <section class="relative w-full bg-cover bg-center" 
             style="background-image: url('/img/fondo-nexus.jpg');">

        {{-- capa ligera para dar contraste al texto --}}
        <div class="absolute inset-0 bg-black/10"></div>

        <div class="relative max-w-7xl mx-auto px-8 py-24 grid grid-cols-1 md:grid-cols-2 gap-12">

            {{-- TEXTO --}}
            <div class="flex flex-col justify-center">

                <h2 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-[#6F73BF] leading-tight drop-shadow">
                    Encuentra lo<br> que buscas
                </h2>

                <p class="font-bold text-[#2128A6] mt-4 text-lg max-w-md">
                    Productos seleccionados para  <br> tu estilo de vida.
                </p>
<div class="flex flex-col justify-center max-w-md">
             <button
    class="mt-6 inline-flex items-center justify-center 
           bg-[#30D9C8] hover:bg-[#77D9CF]
           text-white text-2xl font-semibold
           px-6 py-2 rounded-full shadow-lg transition
          ">
    Comprar ahora
</button>
</div>

            </div>

            {{-- COLUMNA DERECHA (solo deja ver la imagen del fondo) --}}
            <div class="hidden md:block"></div>

        </div>
    </section>

    {{-- MARCAS --}}
    <section class="bg-[#f4f6fb]">
        <div class="max-w-7xl mx-auto px-8 py-10 flex flex-wrap gap-10 items-center justify-between">
            <img src="/img/jbl.png" class="h-22 object-contain opacity-80 hover:opacity-100 transition" alt="JBL">
            <img src="/img/boss.png" class="h-13 object-contain opacity-80 hover:opacity-100 transition" alt="Boss">
            <img src="/img/lenovo.png" class="h-16 object-contain opacity-80 hover:opacity-100 transition" alt="Lenovo">
            <img src="/img/zara.png" class="h-23 object-contain opacity-80 hover:opacity-100 transition" alt="Zara">
            <img src="/img/philips.png" class="h-18 object-contain opacity-80 hover:opacity-100 transition" alt="Philips">
        </div>
    </section>

    {{-- PRODUCTOS NUEVOS (CARRUSEL) --}}
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-8">

        <h3 class="text-2xl font-bold text-gray-900 mb-8">
            Productos nuevos
        </h3>

        <div class="relative">

            <!-- Flecha izquierda -->
            <button id="btnLeft"
                class="hidden md:flex items-center justify-center absolute -left-4 top-1/2 -translate-y-1/2
                       bg-white shadow-md rounded-full p-3 z-10 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Carrusel -->
            <div id="carouselProducts"
                 class="flex gap-8 overflow-x-auto scroll-smooth no-scrollbar snap-x snap-mandatory pb-3">

                {{-- CARD 1 --}}
                <article
                    class="min-w-[260px] bg-white rounded-2xl shadow-md hover:shadow-xl transition p-5 flex flex-col justify-between snap-start">
                    <div>
                        <img src="img/tabk10.jpg" class="rounded-xl mb-4 w-full object-cover" alt="Televisión Retro">
                        <h4 class="font-semibold text-gray-900">Tab K10 Lenovo </h4>
                        <p class="text-sm text-gray-500 mt-1">
                            El tamaño de la pantalla de 10.3 pulgadas y la resolución de 1920 x 1200 permiten imágenes fluidas, lectura de contenido fácil 
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="font-bold text-[#3d50ff]">Q 1800.00</p>
                        <button
                            class="mt-3 w-full bg-[#3d50ff] hover:bg-[#2936c7] text-white text-sm font-semibold py-2.5 rounded-lg">
                            Añadir al carrito
                        </button>
                    </div>
                </article>

                {{-- CARD 2 --}}
                <article
                    class="min-w-[260px] bg-white rounded-2xl shadow-md hover:shadow-xl transition p-5 flex flex-col justify-between snap-start">
                    <div>
                        <img src="/img/especieros.png" class="rounded-xl mb-4 w-full object-cover" alt="Especieros">
                        <h4 class="font-semibold text-gray-900">Especieros</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            Juego de contenedores elegantes para cocina.
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="font-bold text-[#3d50ff]">Q 350.00</p>
                        <button
                            class="mt-3 w-full bg-[#3d50ff] hover:bg-[#2936c7] text-white text-sm font-semibold py-2.5 rounded-lg">
                            Añadir al carrito
                        </button>
                    </div>
                </article>

                {{-- CARD 3 --}}
                <article
                    class="min-w-[260px] bg-white rounded-2xl shadow-md hover:shadow-xl transition p-5 flex flex-col justify-between snap-start">
                    <div>
                        <img src="/img/guantes.png" class="rounded-xl mb-4 w-full object-cover" alt="Guantes">
                        <h4 class="font-semibold text-gray-900">Guantes</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            Guantes deportivos de alta resistencia.
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="font-bold text-[#3d50ff]">Q 120.00</p>
                        <button
                            class="mt-3 w-full bg-[#3d50ff] hover:bg-[#2936c7] text-white text-sm font-semibold py-2.5 rounded-lg">
                            Añadir al carrito
                        </button>
                    </div>
                </article>

                {{-- CARD 4 --}}
                <article
                    class="min-w-[260px] bg-white rounded-2xl shadow-md hover:shadow-xl transition p-5 flex flex-col justify-between snap-start">
                    <div>
                        <img src="/img/tiro-arco.png" class="rounded-xl mb-4 w-full object-cover" alt="Tiro al arco">
                        <h4 class="font-semibold text-gray-900">Tiro al arco</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            Set de arco para práctica recreativa.
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="font-bold text-[#3d50ff]">Q 750.00</p>
                        <button
                            class="mt-3 w-full bg-[#3d50ff] hover:bg-[#2936c7] text-white text-sm font-semibold py-2.5 rounded-lg">
                            Añadir al carrito
                        </button>
                    </div>
                </article>

                {{-- CARD NUEVA (5ª) --}}
                <article
                    class="min-w-[260px] bg-white rounded-2xl shadow-md hover:shadow-xl transition p-5 flex flex-col justify-between snap-start">
                    <div>
                        <img src="/img/audifonos.png" class="rounded-xl mb-4 w-full object-cover" alt="Audífonos">
                        <h4 class="font-semibold text-gray-900">Audífonos inalámbricos</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            Sonido nítido y cómodo.
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="font-bold text-[#3d50ff]">Q 420.00</p>
                        <button
                            class="mt-3 w-full bg-[#3d50ff] hover:bg-[#2936c7] text-white text-sm font-semibold py-2.5 rounded-lg">
                            Añadir al carrito
                        </button>
                    </div>
                </article>

            </div>

            <!-- Flecha derecha -->
            <button id="btnRight"
                class="hidden md:flex items-center justify-center absolute -right-4 top-1/2 -translate-y-1/2
                       bg-white shadow-md rounded-full p-3 z-10 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5l7 7-7 7" />
                </svg>
            </button>

        </div>

        {{-- BOTÓN VER TODO --}}
        <div class="text-center mt-12">
            <button
                class="inline-flex items-center justify-center bg-[#3d50ff] hover:bg-[#2936c7] text-white font-semibold px-10 py-3 rounded-full shadow-md transition">
                Ver todo lo nuevo
            </button>
        </div>

    </div>
</section>

 {{-- BENEFICIOS --}}
    <section class="bg-[#f4f6fb] py-12">
        <div class="max-w-5xl mx-auto px-6 grid gap-10 md:grid-cols-3 text-center">

            {{-- Envío gratis 1 --}}
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-[#3d50ff]/10 flex items-center justify-center mb-3">
                    {{-- icono camión --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         class="w-6 h-6 text-[#3d50ff]" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 7h10v9H3zM13 10h3.5L21 13v3h-8zM7 18a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h4 class="font-semibold text-[#3d50ff]">Envío gratis</h4>
                <p class="text-sm text-gray-600 mt-1">
                    En compras mayores a Q300.
                </p>
            </div>

            {{-- Envío gratis 2 / soporte --}}
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-[#3d50ff]/10 flex items-center justify-center mb-3">
                    {{-- icono soporte / callcenter --}}
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
     stroke-width="2" stroke="currentColor"
     class="w-7 h-7 text-[#3d50ff]">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 1a9 9 0 00-9 9v4a3 3 0 003 3h1v-7H6V10a6 6 0 0112 0v3h-1v7h1a3 3 0 003-3v-4a9 9 0 00-9-9zm-4 18h4m4 0h-4" />
</svg>

                </div>
                <h4 class="font-semibold text-[#3d50ff]">Atención 24/7</h4>
                <p class="text-sm text-gray-600 mt-1">
                    30 días para reportar inconvenientes.
                </p>
            </div>

            {{-- Garantía --}}
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-[#3d50ff]/10 flex items-center justify-center mb-3">
                    {{-- icono estrella --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         class="w-6 h-6 text-[#3d50ff]" fill="currentColor">
                        <path
                            d="M12 2.5l2.9 5.88 6.1.89-4.4 4.3 1 6.03L12 17.5l-5.6 2.93 1-6.03-4.4-4.3 6.1-.89L12 2.5z" />
                    </svg>
                </div>
                <h4 class="font-semibold text-[#3d50ff]">Garantía</h4>
                <p class="text-sm text-gray-600 mt-1">
                    3–12 meses según el producto.
                </p>
            </div>

        </div>
    </section>

    {{-- EXPLORAR MÁS --}}
    <section class="bg-[#f4f6fb] py-16">
        <div class="max-w-7xl mx-auto px-8">

            <h3 class="text-lg font-semibold text-[#3d50ff] mb-4">
                Explorar más
            </h3>

            <div
                class="bg-white rounded-3xl shadow-md overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-0 items-center">

                {{-- Imagen izquierda --}}
                <div class="w-full h-full">
                    <img src="/img/explorar-mas.png"
                         alt="Explora más productos"
                         class="w-full h-full object-cover">
                </div>

                {{-- Texto derecha --}}
                <div class="px-8 py-10 md:py-14 flex flex-col justify-center">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-[#3d50ff] leading-tight">
                        Encuentra lo<br>
                        que te inspira...
                    </h2>

                    <p class="mt-4 text-gray-600 text-base md:text-lg max-w-md">
                        Tecnología, hogar y más, todo en un solo lugar.
                    </p>

                    <button
                        class="mt-7 inline-flex items-center justify-center bg-[#3d50ff] hover:bg-[#2936c7]
                               text-white font-semibold px-8 py-3 rounded-full shadow-md transition">
                        Ver productos
                    </button>
                </div>

            </div>

        </div>
    </section>
{{-- REVIEWS DE CLIENTES --}}
<section class="bg-[#f4f6fb] py-16">
    <div class="max-w-7xl mx-auto px-8">

        <h3 class="text-2xl font-bold text-gray-900 mb-2">
            Lo que dicen nuestros clientes
        </h3>
        <p class="text-sm text-gray-500 mb-8">
            Reseñas de personas que ya confiaron en Nexus.
        </p>

        {{-- TARJETAS DE RESEÑAS --}}
        <div class="grid gap-6 md:grid-cols-2">

            {{-- REVIEW 1 --}}
            <article class="bg-white rounded-2xl shadow-md p-6 flex gap-4 items-start">
                {{-- FOTO CLIENTE --}}
                <img src="/img/cliente-1.jpg"
                     alt="Foto cliente"
                     class="w-20 h-20 rounded-lg object-cover flex-shrink-0">

                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900 leading-tight">Elisa Veliz</h4>
                            <p class="text-xs text-gray-400">24 Octubre 2025</p>
                        </div>

                        {{-- ESTRELLAS --}}
                        <div class="flex text-[#3d50ff] text-xs">
                            <i class="fa-solid fa-star mx-0.5"></i>
                            <i class="fa-solid fa-star mx-0.5"></i>
                            <i class="fa-solid fa-star mx-0.5"></i>
                            <i class="fa-solid fa-star mx-0.5"></i>
                            <i class="fa-solid fa-star mx-0.5"></i>
                        </div>
                    </div>

                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    </p>
                </div>
            </article>

            {{-- REVIEW 2 --}}
            <article class="bg-white rounded-2xl shadow-md p-6 flex gap-4 items-start">
                {{-- FOTO CLIENTE --}}
                <img src="/img/cliente-2.jpg"
                     alt="Foto cliente"
                     class="w-20 h-20 rounded-lg object-cover flex-shrink-0">

                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900 leading-tight">Luis Martínez</h4>
                            <p class="text-xs text-gray-400">18 Octubre 2025</p>
                        </div>

                        {{-- ESTRELLAS --}}
                        <div class="flex text-[#3d50ff] text-xs">
                            <i class="fa-solid fa-star mx-0.5"></i>
                            <i class="fa-solid fa-star mx-0.5"></i>
                            <i class="fa-solid fa-star mx-0.5"></i>
                            <i class="fa-solid fa-star mx-0.5"></i>
                            <i class="fa-solid fa-star mx-0.5"></i>
                        </div>
                    </div>

                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    </p>
                </div>
            </article>

        </div>

    </div>
</section>


    </div>
</section>


</div>
@endsection