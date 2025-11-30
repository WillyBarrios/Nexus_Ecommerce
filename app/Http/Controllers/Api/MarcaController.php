<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    /**
     * Listar todas las marcas
     * GET /api/marcas
     */
    public function index()
    {
        $marcas = Marca::withCount('productos')->get();
        
        return response()->json([
            'success' => true,
            'data' => $marcas
        ]);
    }
    
    /**
     * Ver detalle de una marca
     * GET /api/marcas/{id}
     */
    public function show($id)
    {
        $marca = Marca::withCount('productos')->find($id);
        
        if (!$marca) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $marca
        ]);
    }
    
    /**
     * Crear una nueva marca
     * POST /api/marcas
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_marca' => 'required|string|max:100|unique:marcas,nombre_marca',
            'descripcion' => 'nullable|string|max:255'
        ], [
            'nombre_marca.required' => 'El nombre de la marca es requerido',
            'nombre_marca.unique' => 'Esta marca ya existe'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $marca = Marca::create($request->only(['nombre_marca', 'descripcion']));
        
        return response()->json([
            'success' => true,
            'message' => 'Marca creada exitosamente',
            'data' => $marca
        ], 201);
    }
    
    /**
     * Actualizar una marca
     * PUT /api/marcas/{id}
     */
    public function update(Request $request, $id)
    {
        $marca = Marca::find($id);
        
        if (!$marca) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nombre_marca' => 'string|max:100|unique:marcas,nombre_marca,' . $id . ',id_marca',
            'descripcion' => 'nullable|string|max:255'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $marca->update($request->only(['nombre_marca', 'descripcion']));
        
        return response()->json([
            'success' => true,
            'message' => 'Marca actualizada exitosamente',
            'data' => $marca
        ]);
    }
    
    /**
     * Eliminar una marca
     * DELETE /api/marcas/{id}
     */
    public function destroy($id)
    {
        $marca = Marca::find($id);
        
        if (!$marca) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }
        
        // Verificar si tiene productos asociados
        if ($marca->productos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la marca porque tiene productos asociados'
            ], 400);
        }
        
        $marca->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Marca eliminada exitosamente'
        ]);
    }
    
    /**
     * Obtener productos de una marca
     * GET /api/marcas/{id}/productos
     */
    public function productos($id)
    {
        $marca = Marca::find($id);
        
        if (!$marca) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }
        
        $productos = $marca->productos()
                          ->with(['categoria', 'imagenes'])
                          ->where('estado', 'activo')
                          ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'marca' => $marca,
                'productos' => $productos
            ]
        ]);
    }
}
