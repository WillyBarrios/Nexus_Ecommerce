<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus</title>

    {{-- FONT AWESOME (ICONOS) --}}
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          integrity="sha512-RXf+QSDCUQs6f8f7BvV0fUlj4R1pSfgcB2EKiC5Jy5VaUZt7dQoyW3n5FaJd6jK7U5JxoK6+5p1jKJ8X5m7HPg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- TAILWIND / VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-900">

    {{-- NAVBAR--}}
    <header class="w-full bg-white/70 backdrop-blur-xl border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-8 py-4 flex items-center justify-between">

            {{-- LOGO --}}
            <a href="/" class="flex items-center">
                <img src="/img/logo-nexus.png" class="h-10" alt="Nexus Logo">
            </a>

            {{-- MENÚ --}}
            <nav class="hidden md:flex gap-12">
                <a href="#" class="text-[#1d2ea3] text-lg font-medium hover:text-[#3d50ff] transition">
                    Categorias
                </a>
                <a href="#" class="text-[#1d2ea3] text-lg font-medium hover:text-[#3d50ff] transition">
                    Ofertas
                </a>
                <a href="#" class="text-[#1d2ea3] text-lg font-medium hover:text-[#3d50ff] transition">
                    Contacto
                </a>
            </nav>

            {{-- DERECHA: BUSCADOR + ICONOS --}}
            <div class="flex items-center gap-8">

                {{-- BUSCADOR --}}
                <div class="flex items-center border border-[#1d2ea3] rounded-full px-4 py-2 bg-white shadow-sm">
                    <input 
                        type="text" 
                        placeholder="Qué estás buscando?" 
                        class="bg-transparent outline-none w-40 md:w-56 text-sm text-[#1d2ea3] placeholder-gray-500">

                    {{-- ICONO BÚSQUEDA --}}
                    <button>
                        <i class="fa-solid fa-magnifying-glass text-[#1d2ea3] text-lg cursor-pointer"></i>
                    </button>
                </div>

                {{-- ICONO USUARIO --}}
                <i class="fa-regular fa-user text-2xl text-[#1d2ea3] hover:text-[#3d50ff] cursor-pointer"></i>

                {{-- ICONO CARRITO --}}
                <i class="fa-solid fa-cart-shopping text-2xl text-[#1d2ea3] hover:text-[#3d50ff] cursor-pointer"></i>

            </div>
        </div>
    </header>

    {{-- CONTENIDO --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="mt-20 py-10 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm">© {{ date('Y') }} Nexus. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
