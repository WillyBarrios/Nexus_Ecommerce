<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador Admin de Usuarios
 */
class UsuarioAdminController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro por rol
        if ($request->has('rol') && $request->rol != '') {
            $query->where('id_rol', $request->rol);
        }

        $usuarios = $query->orderBy('fecha_creacion', 'desc')->paginate(15);
        
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:150',
            'correo_electronico' => 'required|email|max:150|unique:usuarios,correo_electronico',
            'contrasena' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'id_rol' => 'required|in:1,2,3'
        ], [
            'nombre_completo.required' => 'El nombre completo es requerido',
            'correo_electronico.required' => 'El correo electrónico es requerido',
            'correo_electronico.unique' => 'Ya existe un usuario con ese correo',
            'contrasena.required' => 'La contraseña es requerida',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'id_rol.required' => 'El rol es requerido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['contrasena'] = Hash::make($request->contrasena);

        User::create($data);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:150',
            'correo_electronico' => 'required|email|max:150|unique:usuarios,correo_electronico,' . $id . ',id_usuario',
            'contrasena' => 'nullable|string|min:6',
            'telefono' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'id_rol' => 'required|in:1,2,3'
        ], [
            'nombre_completo.required' => 'El nombre completo es requerido',
            'correo_electronico.required' => 'El correo electrónico es requerido',
            'correo_electronico.unique' => 'Ya existe un usuario con ese correo',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'id_rol.required' => 'El rol es requerido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('contrasena');
        
        // Solo actualizar contraseña si se proporciona
        if ($request->filled('contrasena')) {
            $data['contrasena'] = Hash::make($request->contrasena);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        
        // No permitir eliminar al usuario actual
        if (auth()->check() && auth()->user()->id_usuario == $id) {
            return redirect()->back()
                ->with('error', 'No puedes eliminar tu propio usuario');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}
