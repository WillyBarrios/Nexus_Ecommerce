<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MarcaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador Admin de Marcas
 * Maneja las vistas del panel de administración
 */
class MarcaAdminController extends Controller
{
    protected $marcaService;
    
    public function __construct(MarcaService $marcaService)
    {
        $this->marcaService = $marcaService;
    }
    
    /**
     * Listar todas las marcas
     */
    public function index()
    {
        $marcas = $this->marcaService->listarMarcas(15);
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

        try {
            $this->marcaService->crearMarca($request->only(['nombre_marca', 'descripcion']));

            return redirect()->route('admin.marcas.index')
                ->with('success', 'Marca creada exitosamente');
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
            $marca = $this->marcaService->obtenerMarca($id);
            return view('admin.marcas.edit', compact('marca'));
        } catch (\Exception $e) {
            return redirect()->route('admin.marcas.index')
                ->withErrors(['error' => 'Marca no encontrada']);
        }
    }

    /**
     * Actualizar marca
     */
    public function update(Request $request, $id)
    {
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

        try {
            $this->marcaService->actualizarMarca($id, $request->only(['nombre_marca', 'descripcion']));

            return redirect()->route('admin.marcas.index')
                ->with('success', 'Marca actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Eliminar marca
     */
    public function destroy($id)
    {
        try {
            $this->marcaService->eliminarMarca($id);

            return redirect()->route('admin.marcas.index')
                ->with('success', 'Marca eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
