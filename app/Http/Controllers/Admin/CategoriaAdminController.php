<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador Admin de Categorías
 * Maneja las vistas del panel de administración
 */
class CategoriaAdminController extends Controller
{
    protected $categoriaService;
    
    public function __construct(CategoriaService $categoriaService)
    {
        $this->categoriaService = $categoriaService;
    }
    
    /**
     * Listar todas las categorías
     */
    public function index()
    {
        $categorias = $this->categoriaService->listarCategorias(15);
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Guardar nueva categoría
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_categoria' => 'required|string|max:150|unique:categorias,nombre_categoria',
            'descripcion' => 'nullable|string|max:255'
        ], [
            'nombre_categoria.required' => 'El nombre de la categoría es requerido',
            'nombre_categoria.unique' => 'Ya existe una categoría con ese nombre'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->categoriaService->crearCategoria($request->only(['nombre_categoria', 'descripcion']));

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría creada exitosamente');
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
            $categoria = $this->categoriaService->obtenerCategoria($id);
            return view('admin.categorias.edit', compact('categoria'));
        } catch (\Exception $e) {
            return redirect()->route('admin.categorias.index')
                ->withErrors(['error' => 'Categoría no encontrada']);
        }
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre_categoria' => 'required|string|max:150|unique:categorias,nombre_categoria,' . $id . ',id_categoria',
            'descripcion' => 'nullable|string|max:255'
        ], [
            'nombre_categoria.required' => 'El nombre de la categoría es requerido',
            'nombre_categoria.unique' => 'Ya existe una categoría con ese nombre'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->categoriaService->actualizarCategoria($id, $request->only(['nombre_categoria', 'descripcion']));

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Eliminar categoría
     */
    public function destroy($id)
    {
        try {
            $this->categoriaService->eliminarCategoria($id);

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
