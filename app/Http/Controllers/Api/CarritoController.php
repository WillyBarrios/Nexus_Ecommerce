<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CarritoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador del Carrito de Compras
 * 
 * Maneja las peticiones HTTP del carrito y delega la lógica al servicio
 */
class CarritoController extends Controller
{
    protected $carritoService;
    
    public function __construct(CarritoService $carritoService)
    {
        $this->carritoService = $carritoService;
    }
    
    /**
     * Ver carrito del usuario
     * GET /api/carrito
     */
    public function index(Request $request)
    {
        $carrito = $this->carritoService->obtenerCarritoConDetalles($request->user()->id_usuario);
        
        return response()->json([
            'success' => true,
            'data' => $carrito
        ]);
    }
    
    /**
     * Agregar producto al carrito
     * POST /api/carrito/agregar
     * 
     * Body:
     * {
     *   "id_producto": 1,
     *   "cantidad": 2
     * }
     */
    public function agregar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1'
        ], [
            'id_producto.required' => 'El producto es requerido',
            'id_producto.exists' => 'El producto no existe',
            'cantidad.required' => 'La cantidad es requerida',
            'cantidad.min' => 'La cantidad debe ser al menos 1'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $resultado = $this->carritoService->agregarProducto(
                $request->user()->id_usuario,
                $request->id_producto,
                $request->cantidad
            );
            
            return response()->json([
                'success' => true,
                'message' => $resultado['mensaje'],
                'data' => $resultado['carrito']
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Actualizar cantidad de un producto en el carrito
     * PUT /api/carrito/actualizar/{id_detalle}
     * 
     * Body:
     * {
     *   "cantidad": 3
     * }
     */
    public function actualizar(Request $request, $id_detalle)
    {
        $validator = Validator::make($request->all(), [
            'cantidad' => 'required|integer|min:1'
        ], [
            'cantidad.required' => 'La cantidad es requerida',
            'cantidad.min' => 'La cantidad debe ser al menos 1'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $carrito = $this->carritoService->actualizarCantidad(
                $request->user()->id_usuario,
                $id_detalle,
                $request->cantidad
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Cantidad actualizada',
                'data' => $carrito
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getMessage() === 'No tienes permiso para modificar este item' ? 403 : 400;
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
    
    /**
     * Eliminar un producto del carrito
     * DELETE /api/carrito/eliminar/{id_detalle}
     */
    public function eliminar(Request $request, $id_detalle)
    {
        try {
            $carrito = $this->carritoService->eliminarProducto(
                $request->user()->id_usuario,
                $id_detalle
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado del carrito',
                'data' => $carrito
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getMessage() === 'No tienes permiso para eliminar este item' ? 403 : 400;
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
    
    /**
     * Vaciar todo el carrito
     * DELETE /api/carrito/vaciar
     */
    public function vaciar(Request $request)
    {
        $carrito = $this->carritoService->vaciarCarrito($request->user()->id_usuario);
        
        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado exitosamente',
            'data' => [
                'id' => $carrito->id_carrito,
                'items' => [],
                'total_items' => 0,
                'total' => 0
            ]
        ]);
    }
}
