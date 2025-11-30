@extends('admin.layout')

@section('title', 'Pedidos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cart"></i> Pedidos</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filtros -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pedidos.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                    <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id_pedido }}</td>
                        <td><strong>{{ $pedido->numero_pedido }}</strong></td>
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
                        <td>{{ $pedido->fecha_creacion->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.pedidos.show', $pedido->id_pedido) }}" 
                               class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay pedidos registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>
@endsection
