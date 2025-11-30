<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías
     * GET /api/categorias
     */
    public function index()
    {
        $categorias = Categoria::withCount('productos')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categorias
        ]);
    }
    
    /**
     * Ver detalle de una categoría
     * GET /api/categorias/{id}
     */
    public function show($id)
    {
        $categoria = Categoria::withCount('productos')->find($id);
        
        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $categoria
        ]);
    }
    
    /**
     * Crear una nueva categoría
     * POST /api/categorias
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_categoria' => 'required|string|max:100|unique:categorias,nombre_categoria',
            'descripcion' => 'nullable|string|max:255'
        ], [
            'nombre_categoria.required' => 'El nombre de la categoría es requerido',
            'nombre_categoria.unique' => 'Esta categoría ya existe'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $categoria = Categoria::create($request->only(['nombre_categoria', 'descripcion']));
        
        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente',
            'data' => $categoria
        ], 201);
    }
    
    /**
     * Actualizar una categoría
     * PUT /api/categorias/{id}
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        
        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nombre_categoria' => 'string|max:100|unique:categorias,nombre_categoria,' . $id . ',id_categoria',
            'descripcion' => 'nullable|string|max:255'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $categoria->update($request->only(['nombre_categoria', 'descripcion']));
        
        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente',
            'data' => $categoria
        ]);
    }
    
    /**
     * Eliminar una categoría
     * DELETE /api/categorias/{id}
     */
    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        
        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
        
        // Verificar si tiene productos asociados
        if ($categoria->productos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la categoría porque tiene productos asociados'
            ], 400);
        }
        
        $categoria->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }
    
    /**
     * Obtener productos de una categoría
     * GET /api/categorias/{id}/productos
     */
    public function productos($id)
    {
        $categoria = Categoria::find($id);
        
        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
        
        $productos = $categoria->productos()
                              ->with(['marca', 'imagenes'])
                              ->where('estado', 'activo')
                              ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'categoria' => $categoria,
                'productos' => $productos
            ]
        ]);
    }
}
