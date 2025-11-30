<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador Admin de Categorías
 */
class CategoriaAdminController extends Controller
{
    /**
     * Listar todas las categorías
     */
    public function index()
    {
        $categorias = Categoria::withCount('productos')->paginate(15);
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

        Categoria::create($request->all());

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

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

        $categoria->update($request->all());

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    /**
     * Eliminar categoría
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        
        // Verificar si tiene productos asociados
        if ($categoria->productos()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados');
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
}
