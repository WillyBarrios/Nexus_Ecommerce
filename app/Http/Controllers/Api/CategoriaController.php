<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CategoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    protected $categoriaService;
    
    public function __construct(CategoriaService $categoriaService)
    {
        $this->categoriaService = $categoriaService;
    }
    
    /**
     * Listar todas las categorías
     * GET /api/categorias
     */
    public function index()
    {
        $categorias = $this->categoriaService->listarCategorias();
        
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
        try {
            $categoria = $this->categoriaService->obtenerCategoria($id);
            
            return response()->json([
                'success' => true,
                'data' => $categoria
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
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
        
        $categoria = $this->categoriaService->crearCategoria($request->only(['nombre_categoria', 'descripcion']));
        
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
        
        try {
            $categoria = $this->categoriaService->actualizarCategoria($id, $request->only(['nombre_categoria', 'descripcion']));
            
            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente',
                'data' => $categoria
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
    }
    
    /**
     * Eliminar una categoría
     * DELETE /api/categorias/{id}
     */
    public function destroy($id)
    {
        try {
            $this->categoriaService->eliminarCategoria($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Obtener productos de una categoría
     * GET /api/categorias/{id}/productos
     */
    public function productos($id)
    {
        try {
            $datos = $this->categoriaService->obtenerProductosCategoria($id);
            
            return response()->json([
                'success' => true,
                'data' => $datos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
    }
}
