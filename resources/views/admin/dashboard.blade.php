@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total Productos</h6>
                <h3>{{ $stats['total_productos'] }}</h3>
                <small class="text-success">{{ $stats['productos_activos'] }} activos</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total Pedidos</h6>
                <h3>{{ $stats['total_pedidos'] }}</h3>
                <small class="text-warning">{{ $stats['pedidos_pendientes'] }} pendientes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total Usuarios</h6>
                <h3>{{ $stats['total_usuarios'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Ventas Totales</h6>
                <h3>${{ number_format($stats['ventas_totales'], 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Productos con Stock Bajo -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5><i class="bi bi-exclamation-triangle"></i> Stock Bajo</h5>
            </div>
            <div class="card-body">
                @if($productos_stock_bajo->isEmpty())
                    <p class="text-muted">No hay productos con stock bajo</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Categoría</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos_stock_bajo as $producto)
                                <tr>
                                    <td>{{ $producto->nombre_producto }}</td>
                                    <td><span class="badge bg-danger">{{ $producto->existencia }}</span></td>
                                    <td>{{ $producto->categoria->nombre_categoria }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Últimos Pedidos -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-cart"></i> Últimos Pedidos</h5>
            </div>
            <div class="card-body">
                @if($ultimos_pedidos->isEmpty())
                    <p class="text-muted">No hay pedidos recientes</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimos_pedidos as $pedido)
                                <tr>
                                    <td>#{{ $pedido->id_pedido }}</td>
                                    <td>{{ $pedido->usuario->nombre_completo }}</td>
                                    <td>${{ number_format($pedido->monto_total, 2) }}</td>
                                    <td>
                                        @if($pedido->estado == 'pendiente')
                                            <span class="badge bg-warning">Pendiente</span>
                                        @elseif($pedido->estado == 'pagado')
                                            <span class="badge bg-info">Pagado</span>
                                        @elseif($pedido->estado == 'enviado')
                                            <span class="badge bg-primary">Enviado</span>
                                        @elseif($pedido->estado == 'entregado')
                                            <span class="badge bg-success">Entregado</span>
                                        @else
                                            <span class="badge bg-danger">Cancelado</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
