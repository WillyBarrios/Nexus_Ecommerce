@extends('admin.layout')

@section('title', 'Editar Marca')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-award"></i> Editar Marca</h2>
    <a href="{{ route('admin.marcas.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.marcas.update', $marca->id_marca) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nombre_marca" class="form-label">Nombre *</label>
                <input type="text" 
                       class="form-control @error('nombre_marca') is-invalid @enderror" 
                       id="nombre_marca" 
                       name="nombre_marca" 
                       value="{{ old('nombre_marca', $marca->nombre_marca) }}"
                       required>
                @error('nombre_marca')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                          id="descripcion" 
                          name="descripcion" 
                          rows="3">{{ old('descripcion', $marca->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.marcas.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
