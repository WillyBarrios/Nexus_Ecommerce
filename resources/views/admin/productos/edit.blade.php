@extends('admin.layout')

@section('title', 'Editar Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pencil"></i> Editar Producto</h2>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.productos.update', $producto->id_producto) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre del Producto *</label>
                    <input type="text" name="nombre_producto" class="form-control @error('nombre_producto') is-invalid @enderror" 
                           value="{{ old('nombre_producto', $producto->nombre_producto) }}" required>
                    @error('nombre_producto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Precio *</label>
                    <input type="number" step="0.01" name="precio" class="form-control @error('precio') is-invalid @enderror" 
                           value="{{ old('precio', $producto->precio) }}" required>
                    @error('precio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Stock *</label>
                    <input type="number" name="existencia" class="form-control @error('existencia') is-invalid @enderror" 
                           value="{{ old('existencia', $producto->existencia) }}" required>
                    @error('existencia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Categoría *</label>
                    <select name="id_categoria" class="form-select @error('id_categoria') is-invalid @enderror" required>
                        <option value="">Seleccionar...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" 
                                {{ old('id_categoria', $producto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nombre_categoria }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Marca *</label>
                    <select name="id_marca" class="form-select @error('id_marca') is-invalid @enderror" required>
                        <option value="">Seleccionar...</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id_marca }}" 
                                {{ old('id_marca', $producto->id_marca) == $marca->id_marca ? 'selected' : '' }}>
                                {{ $marca->nombre_marca }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_marca')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="activo" {{ old('estado', $producto->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $producto->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            </div>
        </form>
    </div>
</div>
@endsection
