@extends('admin.layout')

@section('title', 'Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Usuarios</h2>
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Usuario
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

<!-- Filtros -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="rol" class="form-label">Rol</label>
                <select name="rol" id="rol" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" {{ request('rol') == '1' ? 'selected' : '' }}>Administrador</option>
                    <option value="2" {{ request('rol') == '2' ? 'selected' : '' }}>Vendedor</option>
                    <option value="3" {{ request('rol') == '3' ? 'selected' : '' }}>Cliente</option>
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
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id_usuario }}</td>
                        <td><strong>{{ $usuario->nombre_completo }}</strong></td>
                        <td>{{ $usuario->correo_electronico }}</td>
                        <td>{{ $usuario->telefono ?? '-' }}</td>
                        <td>
                            @if($usuario->id_rol == 1)
                                <span class="badge bg-danger">Administrador</span>
                            @elseif($usuario->id_rol == 2)
                                <span class="badge bg-warning">Vendedor</span>
                            @else
                                <span class="badge bg-info">Cliente</span>
                            @endif
                        </td>
                        <td>{{ $usuario->fecha_creacion->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.usuarios.edit', $usuario->id_usuario) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.usuarios.destroy', $usuario->id_usuario) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
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
                        <td colspan="7" class="text-center text-muted">No hay usuarios registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection
