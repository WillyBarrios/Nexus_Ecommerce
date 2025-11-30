<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador Admin de Usuarios
 * Maneja las vistas del panel de administración
 */
class UsuarioAdminController extends Controller
{
    protected $usuarioService;
    
    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }
    
    /**
     * Listar todos los usuarios
     */
    public function index(Request $request)
    {
        $filtros = $request->only(['rol']);
        $usuarios = $this->usuarioService->listarUsuarios($filtros);
        
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

        try {
            $this->usuarioService->crearUsuario($request->all());

            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $usuario = $this->usuarioService->obtenerUsuario($id);
            return view('admin.usuarios.edit', compact('usuario'));
        } catch (\Exception $e) {
            return redirect()->route('admin.usuarios.index')
                ->withErrors(['error' => 'Usuario no encontrado']);
        }
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
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

        try {
            $this->usuarioService->actualizarUsuario($id, $request->all());

            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        try {
            $idUsuarioActual = auth()->check() ? auth()->user()->id_usuario : null;
            $this->usuarioService->eliminarUsuario($id, $idUsuarioActual);

            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
