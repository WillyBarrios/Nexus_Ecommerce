<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-[#F2F2F2] flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <img src="{{ asset('img/logo-nexus.png') }}" alt="Logo" class="h-16 mx-auto">
        </div>

        <div class="bg-white p-12 rounded-3xl shadow-2xl">
            <h1 class="text-3xl font-bold text-center text-[#2128A5] mb-8">
                Crear Cuenta
            </h1>

            <form method="POST" action="/register" class="space-y-6">
                @csrf

                <input type="text" name="name" required placeholder="Nombre completo"
                       class="w-full px-5 py-3 border-2 border-[#2128A5] rounded-xl bg-[#f2f2f2]">

                <input type="email" name="email" required placeholder="Correo electrónico"
                       class="w-full px-5 py-3 border-2 border-[#2128A5] rounded-xl bg-[#f2f2f2]">

                <input type="password" name="password" required placeholder="Contraseña"
                       class="w-full px-5 py-3 border-2 border-[#2128A5] rounded-xl bg-[#f2f2f2]">

                <input type="password" name="password_confirmation" required placeholder="Confirmar contraseña"
                       class="w-full px-5 py-3 border-2 border-[#2128A5] rounded-xl bg-[#f2f2f2]">

                <button type="submit"
                        class="w-full bg-[#2128A5] hover:bg-blue-700 text-white font-bold py-4 rounded-xl text-lg">
                    Crear mi cuenta
                </button>
            </form>

            <p class="text-center text-sm text-[#7072BF] mt-6">
                <a href="/login" class="underline hover:text-[#2128A5]">← Ya tengo cuenta</a>
            </p>
        </div>

        <p class="text-center text-xs text-[#7072BF] mt-10">
            Powered by Nexus © 2025
        </p>
    </div>
</body>
</html>