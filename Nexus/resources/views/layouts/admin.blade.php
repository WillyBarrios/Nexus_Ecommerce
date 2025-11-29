<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Nexus Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body>
    <div class="main-container">
        <header class="top-nav">
            <div class="nav-left">
                <button class="menu-button">☰</button>
                <a href="{{ route('client.home') }}" class="brand">Nexus</a>
                <div class="search-bar">
                    <i data-feather="search"></i>
                    <input type="text" placeholder="Search">
                </div>
            </div>
            <div class="nav-right">
                <button class="icon-button"><i data-feather="moon"></i></button>
                <button class="icon-button"><i data-feather="home"></i></button>
                <button class="icon-button"><i data-feather="bell"></i></button>
                <a href="{{ route('admin.profile') }}" class="user-profile-icon">E</a>
            </div>
        </header>

        <main class="content">
            @yield('content')
        </main>
    </div>

    <script>
      feather.replace()
    </script>
</body>
</html>
