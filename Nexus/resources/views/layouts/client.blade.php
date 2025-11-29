<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nexus')</title>
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
</head>
<body>
    <nav class="navbar">
        <div>
            <a href="{{ route('client.home') }}">Nexus</a>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}">Admin Login</a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; 2024 Nexus. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
