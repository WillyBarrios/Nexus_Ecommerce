<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carrito;
use App\Models\DetalleCarrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador del Carrito de Compras
 * 
 * Maneja todas las operaciones del carrito:
 * - Agregar productos
 * - Ver carrito
 * - Actualizar cantidades
 * - Eliminar productos
 * - Vaciar carrito
 */
class CarritoController extends Controller
{
    /**
     * Obtener o crear carrito del usuario autenticado
     */
    private function obtenerCarrito($usuario)
    {
        // Buscar carrito abierto del usuario
        $carrito = Carrito::where('id_usuario', $usuario->id_usuario)
                         ->abierto()
                         ->first();
        
        // Si no existe, crear uno nuevo
        if (!$carrito) {
            $carrito = Carrito::create([
                'id_usuario' => $usuario->id_usuario,
                'estado' => 'abierto'
            ]);
        }
        
        return $carrito;
    }
    
    /**
     * Ver carrito del usuario
     * GET /api/carrito
     */
    public function index(Request $request)
    {
        $carrito = $this->obtenerCarrito($request->user());
        $carrito->load(['detalles.producto.imagenes']);
        
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
        
        // Verificar que el producto exista y esté activo
        $producto = Producto::find($request->id_producto);
        
        if ($producto->estado !== 'activo') {
            return response()->json([
                'success' => false,
                'message' => 'El producto no está disponible'
            ], 400);
        }
        
        // Verificar stock disponible
        if (!$producto->tieneStock($request->cantidad)) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente. Disponible: ' . $producto->existencia
            ], 400);
        }
        
        // Obtener o crear carrito
        $carrito = $this->obtenerCarrito($request->user());
        
        // Verificar si el producto ya está en el carrito
        $detalle = DetalleCarrito::where('id_carrito', $carrito->id_carrito)
                                ->where('id_producto', $request->id_producto)
                                ->first();
        
        if ($detalle) {
            // Si ya existe, actualizar cantidad
            $nuevaCantidad = $detalle->cantidad + $request->cantidad;
            
            // Verificar stock para la nueva cantidad
            if (!$producto->tieneStock($nuevaCantidad)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente. Ya tienes ' . $detalle->cantidad . ' en el carrito. Disponible: ' . $producto->existencia
                ], 400);
            }
            
            $detalle->update(['cantidad' => $nuevaCantidad]);
            $mensaje = 'Cantidad actualizada en el carrito';
        } else {
            // Si no existe, crear nuevo detalle
            $detalle = DetalleCarrito::create([
                'id_carrito' => $carrito->id_carrito,
                'id_producto' => $request->id_producto,
                'cantidad' => $request->cantidad,
                'precio_unitario' => $producto->precio,
                'subtotal' => $producto->precio * $request->cantidad
            ]);
            $mensaje = 'Producto agregado al carrito';
        }
        
        // Recargar carrito con relaciones
        $carrito->load(['detalles.producto.imagenes']);
        
        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'data' => $carrito
        ], 201);
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
        
        // Buscar detalle del carrito
        $detalle = DetalleCarrito::find($id_detalle);
        
        if (!$detalle) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado en el carrito'
            ], 404);
        }
        
        // Verificar que el detalle pertenece al carrito del usuario
        $carrito = $this->obtenerCarrito($request->user());
        
        if ($detalle->id_carrito !== $carrito->id_carrito) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para modificar este item'
            ], 403);
        }
        
        // Verificar stock disponible
        if (!$detalle->producto->tieneStock($request->cantidad)) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente. Disponible: ' . $detalle->producto->existencia
            ], 400);
        }
        
        // Actualizar cantidad y recalcular subtotal
        $detalle->update([
            'cantidad' => $request->cantidad,
            'subtotal' => $detalle->precio_unitario * $request->cantidad
        ]);
        
        // Recargar carrito
        $carrito->load(['detalles.producto.imagenes']);
        
        return response()->json([
            'success' => true,
            'message' => 'Cantidad actualizada',
            'data' => $carrito
        ]);
    }
    
    /**
     * Eliminar un producto del carrito
     * DELETE /api/carrito/eliminar/{id_detalle}
     */
    public function eliminar(Request $request, $id_detalle)
    {
        $detalle = DetalleCarrito::find($id_detalle);
        
        if (!$detalle) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado en el carrito'
            ], 404);
        }
        
        // Verificar que el detalle pertenece al carrito del usuario
        $carrito = $this->obtenerCarrito($request->user());
        
        if ($detalle->id_carrito !== $carrito->id_carrito) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este item'
            ], 403);
        }
        
        $detalle->delete();
        
        // Recargar carrito
        $carrito->load(['detalles.producto.imagenes']);
        
        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'data' => $carrito
        ]);
    }
    
    /**
     * Vaciar todo el carrito
     * DELETE /api/carrito/vaciar
     */
    public function vaciar(Request $request)
    {
        $carrito = $this->obtenerCarrito($request->user());
        $carrito->vaciar();
        
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
