<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MarcaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    protected $marcaService;
    
    public function __construct(MarcaService $marcaService)
    {
        $this->marcaService = $marcaService;
    }
    
    public function index()
    {
        $marcas = $this->marcaService->listarMarcas();
        
        return response()->json([
            'success' => true,
            'data' => $marcas
        ]);
    }
    
    public function show($id)
    {
        try {
            $marca = $this->marcaService->obtenerMarca($id);
            
            return response()->json([
                'success' => true,
                'data' => $marca
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }
    }
    
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
        
        $marca = $this->marcaService->crearMarca($request->only(['nombre_marca', 'descripcion']));
        
        return response()->json([
            'success' => true,
            'message' => 'Marca creada exitosamente',
            'data' => $marca
        ], 201);
    }
    
    public function update(Request $request, $id)
    {
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
        
        try {
            $marca = $this->marcaService->actualizarMarca($id, $request->only(['nombre_marca', 'descripcion']));
            
            return response()->json([
                'success' => true,
                'message' => 'Marca actualizada exitosamente',
                'data' => $marca
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }
    }
    
    public function destroy($id)
    {
        try {
            $this->marcaService->eliminarMarca($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Marca eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function productos($id)
    {
        try {
            $datos = $this->marcaService->obtenerProductosMarca($id);
            
            return response()->json([
                'success' => true,
                'data' => $datos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }
    }
}
