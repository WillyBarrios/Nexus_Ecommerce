@extends('layouts.admin')

@section('title', 'Perfil de Administrador')

@section('content')
<div class="dashboard-header">
    <div class="greeting">
        <h1>Perfil del Administrador</h1>
        <p>Actualiza tu información personal y contraseña.</p>
    </div>
</div>

<div class="card">
    <h2 class="card-header">Editar Perfil</h2>
    <form>
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" value="Admin User">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" value="admin@nexus.com">
        </div>
        <div class="form-group">
            <label for="password">Nueva Contraseña</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        <button type="submit" class="btn">Actualizar Perfil</button>
    </form>
</div>
@endsection
