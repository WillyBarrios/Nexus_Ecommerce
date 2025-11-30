@extends('admin.layout')

@section('title', 'Detalle del Pedido')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cart"></i> Pedido #{{ $pedido->numero_pedido }}</h2>
    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Información del Pedido -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-info-circle"></i> Información del Pedido</h5>
            </div>
            <div class="card-body">
                <p><strong>Número:</strong> {{ $pedido->numero_pedido }}</p>
                <p><strong>Fecha:</strong> {{ $pedido->fecha_creacion->format('d/m/Y H:i') }}</p>
                <p><strong>Total:</strong> ${{ number_format($pedido->monto_total, 2) }}</p>
                <p><strong>Estado:</strong> 
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
                </p>
                
                <!-- Cambiar Estado -->
                <form action="{{ route('admin.pedidos.updateEstado', $pedido->id_pedido) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="input-group">
                        <select name="estado" class="form-select">
                            <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado" {{ $pedido->estado == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            <option value="enviado" {{ $pedido->estado == 'enviado' ? 'selected' : '' }}>Enviado</option>
                            <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5><i class="bi bi-person"></i> Información del Cliente</h5>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $pedido->usuario->nombre_completo }}</p>
                <p><strong>Email:</strong> {{ $pedido->usuario->correo_electronico }}</p>
                <p><strong>Teléfono:</strong> {{ $pedido->telefono_contacto }}</p>
                <p><strong>Dirección de Envío:</strong><br>{{ $pedido->direccion_envio }}</p>
                @if($pedido->notas)
                    <p><strong>Notas:</strong><br>{{ $pedido->notas }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detalles del Pedido -->
<div class="card">
    <div class="card-header bg-success text-white">
        <h5><i class="bi bi-list"></i> Productos</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre_producto }}</td>
                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                        <td><strong>${{ number_format($pedido->monto_total, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
