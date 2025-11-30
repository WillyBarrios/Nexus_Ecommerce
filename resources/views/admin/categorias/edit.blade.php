@extends('admin.layout')

@section('title', 'Editar Categoría')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-tags"></i> Editar Categoría</h2>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.categorias.update', $categoria->id_categoria) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nombre_categoria" class="form-label">Nombre *</label>
                <input type="text" 
                       class="form-control @error('nombre_categoria') is-invalid @enderror" 
                       id="nombre_categoria" 
                       name="nombre_categoria" 
                       value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}"
                       required>
                @error('nombre_categoria')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                          id="descripcion" 
                          name="descripcion" 
                          rows="3">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
