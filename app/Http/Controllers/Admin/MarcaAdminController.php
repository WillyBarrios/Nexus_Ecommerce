<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador Admin de Marcas
 */
class MarcaAdminController extends Controller
{
    /**
     * Listar todas las marcas
     */
    public function index()
    {
        $marcas = Marca::withCount('productos')->paginate(15);
        return view('admin.marcas.index', compact('marcas'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.marcas.create');
    }

    /**
     * Guardar nueva marca
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_marca' => 'required|string|max:150|unique:marcas,nombre_marca',
            'descripcion' => 'nullable|string|max:255'
        ], [
            'nombre_marca.required' => 'El nombre de la marca es requerido',
            'nombre_marca.unique' => 'Ya existe una marca con ese nombre'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Marca::create($request->all());

        return redirect()->route('admin.marcas.index')
            ->with('success', 'Marca creada exitosamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $marca = Marca::findOrFail($id);
        return view('admin.marcas.edit', compact('marca'));
    }

    /**
     * Actualizar marca
     */
    public function update(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre_marca' => 'required|string|max:150|unique:marcas,nombre_marca,' . $id . ',id_marca',
            'descripcion' => 'nullable|string|max:255'
        ], [
            'nombre_marca.required' => 'El nombre de la marca es requerido',
            'nombre_marca.unique' => 'Ya existe una marca con ese nombre'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $marca->update($request->all());

        return redirect()->route('admin.marcas.index')
            ->with('success', 'Marca actualizada exitosamente');
    }

    /**
     * Eliminar marca
     */
    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        
        // Verificar si tiene productos asociados
        if ($marca->productos()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la marca porque tiene productos asociados');
        }

        $marca->delete();

        return redirect()->route('admin.marcas.index')
            ->with('success', 'Marca eliminada exitosamente');
    }
}
