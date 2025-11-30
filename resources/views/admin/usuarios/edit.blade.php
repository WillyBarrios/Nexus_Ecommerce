@extends('admin.layout')

@section('title', 'Editar Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Editar Usuario</h2>
    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.usuarios.update', $usuario->id_usuario) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Completo *</label>
                    <input type="text" 
                           class="form-control @error('nombre_completo') is-invalid @enderror" 
                           id="nombre_completo" 
                           name="nombre_completo" 
                           value="{{ old('nombre_completo', $usuario->nombre_completo) }}"
                           required>
                    @error('nombre_completo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="correo_electronico" class="form-label">Correo Electrónico *</label>
                    <input type="email" 
                           class="form-control @error('correo_electronico') is-invalid @enderror" 
                           id="correo_electronico" 
                           name="correo_electronico" 
                           value="{{ old('correo_electronico', $usuario->correo_electronico) }}"
                           required>
                    @error('correo_electronico')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" 
                           class="form-control @error('contrasena') is-invalid @enderror" 
                           id="contrasena" 
                           name="contrasena">
                    <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                    @error('contrasena')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" 
                           class="form-control @error('telefono') is-invalid @enderror" 
                           id="telefono" 
                           name="telefono" 
                           value="{{ old('telefono', $usuario->telefono) }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <textarea class="form-control @error('direccion') is-invalid @enderror" 
                          id="direccion" 
                          name="direccion" 
                          rows="2">{{ old('direccion', $usuario->direccion) }}</textarea>
                @error('direccion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol *</label>
                <select class="form-select @error('id_rol') is-invalid @enderror" 
                        id="id_rol" 
                        name="id_rol" 
                        required>
                    <option value="">Seleccionar...</option>
                    <option value="1" {{ old('id_rol', $usuario->id_rol) == '1' ? 'selected' : '' }}>Administrador</option>
                    <option value="2" {{ old('id_rol', $usuario->id_rol) == '2' ? 'selected' : '' }}>Vendedor</option>
                    <option value="3" {{ old('id_rol', $usuario->id_rol) == '3' ? 'selected' : '' }}>Cliente</option>
                </select>
                @error('id_rol')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
