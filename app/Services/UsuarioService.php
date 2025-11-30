<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function listarUsuarios($filtros = [], $perPage = 15)
    {
        $query = User::query();
        
        if (isset($filtros['rol']) && $filtros['rol'] != '') {
            $query->where('id_rol', $filtros['rol']);
        }
        
        return $query->orderBy('fecha_creacion', 'desc')->paginate($perPage);
    }
    
    public function obtenerUsuario($id)
    {
        return User::findOrFail($id);
    }
    
    public function crearUsuario($datos)
    {
        $datos['contrasena'] = Hash::make($datos['contrasena']);
        
        return User::create($datos);
    }
    
    public function actualizarUsuario($id, $datos)
    {
        $usuario = User::findOrFail($id);
        
        $datosActualizar = collect($datos)->except('contrasena')->toArray();
        
        if (isset($datos['contrasena']) && !empty($datos['contrasena'])) {
            $datosActualizar['contrasena'] = Hash::make($datos['contrasena']);
        }
        
        $usuario->update($datosActualizar);
        
        return $usuario;
    }
    
    public function eliminarUsuario($id, $idUsuarioActual)
    {
        if ($id == $idUsuarioActual) {
            throw new \Exception('No puedes eliminar tu propio usuario');
        }
        
        $usuario = User::findOrFail($id);
        $usuario->delete();
        
        return true;
    }
}
