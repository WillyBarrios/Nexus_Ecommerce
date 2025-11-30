@extends('admin.layout')

@section('title', 'Marcas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-award"></i> Marcas</h2>
    <a href="{{ route('admin.marcas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Marca
    </a>
</div>

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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Productos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marcas as $marca)
                    <tr>
                        <td>{{ $marca->id_marca }}</td>
                        <td><strong>{{ $marca->nombre_marca }}</strong></td>
                        <td>{{ $marca->descripcion ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $marca->productos_count }}</span></td>
                        <td>
                            <a href="{{ route('admin.marcas.edit', $marca->id_marca) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.marcas.destroy', $marca->id_marca) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta marca?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay marcas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $marcas->links() }}
        </div>
    </div>
</div>
@endsection
