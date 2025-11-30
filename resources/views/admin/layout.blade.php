<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Nexus Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar a { 
            color: #ecf0f1; 
            text-decoration: none; 
            padding: 12px 20px; 
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar a i {
            font-size: 1.2rem;
            margin-right: 10px;
            width: 25px;
            text-align: center;
        }
        .sidebar a:hover { 
            background-color: rgba(52, 152, 219, 0.2);
            border-left-color: #3498db;
            padding-left: 25px;
        }
        .sidebar a.active { 
            background-color: #3498db;
            border-left-color: #2980b9;
            font-weight: 600;
        }
        .sidebar .p-3 {
            background-color: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .main-content { 
            padding: 30px; 
            background-color: #f8f9fa;
        }
        .stat-card { 
            border-left: 4px solid #3498db;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-radius: 8px;
        }
        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
        }
        .table {
            background-color: white;
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3 text-white">
                    <h4><i class="bi bi-shop"></i> Nexus Admin</h4>
                </div>
                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                    <a href="{{ route('admin.categorias.index') }}" class="{{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Categorías
                    </a>
                    <a href="{{ route('admin.marcas.index') }}" class="{{ request()->routeIs('admin.marcas.*') ? 'active' : '' }}">
                        <i class="bi bi-award"></i> Marcas
                    </a>
                    <a href="{{ route('admin.pedidos.index') }}" class="{{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                        <i class="bi bi-cart"></i> Pedidos
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
