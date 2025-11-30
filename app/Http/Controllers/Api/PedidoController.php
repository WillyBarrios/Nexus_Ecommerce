<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Carrito;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador de Pedidos
 * 
 * Maneja la creación y gestión de pedidos
 */
class PedidoController extends Controller
{
    /**
     * Listar pedidos del usuario
     * GET /api/pedidos
     * 
     * Admin puede ver todos los pedidos
     * Usuario normal solo ve sus propios pedidos
     */
    public function index(Request $request)
    {
        $usuario = $request->user();
        
        // Si es admin (rol 1), ver todos los pedidos
        if ($usuario->id_rol == 1) {
            $query = Pedido::with(['usuario', 'detalles.producto']);
            
            // Filtros opcionales
            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }
            
            if ($request->has('usuario_id')) {
                $query->where('id_usuario', $request->usuario_id);
            }
            
            $pedidos = $query->orderBy('fecha_creacion', 'desc')
                           ->paginate(15);
        } else {
            // Usuario normal solo ve sus pedidos
            $pedidos = Pedido::where('id_usuario', $usuario->id_usuario)
                            ->with(['detalles.producto'])
                            ->orderBy('fecha_creacion', 'desc')
                            ->paginate(15);
        }
        
        return response()->json([
            'success' => true,
            'data' => $pedidos->items(),
            'pagination' => [
                'total' => $pedidos->total(),
                'per_page' => $pedidos->perPage(),
                'current_page' => $pedidos->currentPage(),
                'last_page' => $pedidos->lastPage(),
            ]
        ]);
    }
    
    /**
     * Ver detalle de un pedido
     * GET /api/pedidos/{id}
     */
    public function show(Request $request, $id)
    {
        $pedido = Pedido::with(['usuario', 'detalles.producto', 'pagos'])->find($id);
        
        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        }
        
        $usuario = $request->user();
        
        // Verificar permisos: admin o dueño del pedido
        if ($usuario->id_rol != 1 && $pedido->id_usuario != $usuario->id_usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver este pedido'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'data' => $pedido
        ]);
    }
    
    /**
     * Crear pedido desde el carrito
     * POST /api/pedidos
     * 
     * Body:
     * {
     *   "direccion_envio": "Calle 123",
     *   "telefono_contacto": "555-1234",
     *   "notas": "Entregar en la tarde"
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'direccion_envio' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:30',
            'notas' => 'nullable|string|max:500'
        ], [
            'direccion_envio.required' => 'La dirección de envío es requerida',
            'telefono_contacto.required' => 'El teléfono de contacto es requerido'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $usuario = $request->user();
        
        // Obtener carrito del usuario
        $carrito = Carrito::where('id_usuario', $usuario->id_usuario)
                         ->where('estado', 'abierto')
                         ->with(['detalles.producto'])
                         ->first();
        
        if (!$carrito || $carrito->detalles->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío'
            ], 400);
        }
        
        // Verificar stock de todos los productos
        foreach ($carrito->detalles as $detalle) {
            if (!$detalle->producto->tieneStock($detalle->cantidad)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente para: ' . $detalle->producto->nombre_producto
                ], 400);
            }
        }
        
        DB::beginTransaction();
        
        try {
            // Calcular total
            $total = $carrito->calcularTotal();
            
            // Generar número de pedido único
            $numero_pedido = 'PED-' . date('Ymd') . '-' . str_pad(Pedido::count() + 1, 6, '0', STR_PAD_LEFT);
            
            // Crear pedido
            $pedido = Pedido::create([
                'id_usuario' => $usuario->id_usuario,
                'numero_pedido' => $numero_pedido,
                'monto_total' => $total,
                'estado' => 'pendiente',
                'id_pago' => 0, // Temporal, se actualizará cuando se procese el pago
                'direccion_envio' => $request->direccion_envio,
                'telefono_contacto' => $request->telefono_contacto,
                'notas' => $request->notas
            ]);
            
            // Crear detalles del pedido y reducir stock
            foreach ($carrito->detalles as $detalle) {
                // Crear detalle del pedido (guardando precio actual)
                DetallePedido::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_producto' => $detalle->id_producto,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->producto->precio,
                    'subtotal' => $detalle->producto->precio * $detalle->cantidad
                ]);
                
                // Reducir stock del producto
                $detalle->producto->reducirStock($detalle->cantidad);
                
                // Registrar movimiento de inventario
                MovimientoInventario::create([
                    'id_producto' => $detalle->id_producto,
                    'tipo_movimiento' => 'salida',
                    'cantidad' => $detalle->cantidad,
                    'descripcion' => 'Venta - Pedido #' . $pedido->id_pedido
                ]);
            }
            
            // Cerrar carrito
            $carrito->update(['estado' => 'cerrado']);
            
            DB::commit();
            
            // Cargar relaciones del pedido
            $pedido->load(['detalles.producto']);
            
            return response()->json([
                'success' => true,
                'message' => 'Pedido creado exitosamente',
                'data' => $pedido
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el pedido: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Actualizar estado del pedido
     * PUT /api/pedidos/{id}/estado
     * 
     * Solo Admin puede actualizar el estado
     * 
     * Body:
     * {
     *   "estado": "procesando"
     * }
     */
    public function actualizarEstado(Request $request, $id)
    {
        // Verificar que sea admin
        if ($request->user()->id_rol != 1) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para realizar esta acción'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:pendiente,pagado,enviado,entregado,cancelado'
        ], [
            'estado.required' => 'El estado es requerido',
            'estado.in' => 'Estado inválido'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $pedido = Pedido::find($id);
        
        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        }
        
        $pedido->update(['estado' => $request->estado]);
        
        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado exitosamente',
            'data' => $pedido
        ]);
    }
    
    /**
     * Cancelar pedido
     * DELETE /api/pedidos/{id}
     * 
     * Solo se puede cancelar si está en estado pendiente o procesando
     */
    public function cancelar(Request $request, $id)
    {
        $pedido = Pedido::with(['detalles.producto'])->find($id);
        
        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        }
        
        $usuario = $request->user();
        
        // Verificar permisos: admin o dueño del pedido
        if ($usuario->id_rol != 1 && $pedido->id_usuario != $usuario->id_usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para cancelar este pedido'
            ], 403);
        }
        
        // Verificar si se puede cancelar
        if (!$pedido->puedeCancelarse()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede cancelar un pedido en estado: ' . $pedido->estado
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            // Devolver stock de los productos
            foreach ($pedido->detalles as $detalle) {
                $detalle->producto->aumentarStock($detalle->cantidad);
                
                // Registrar movimiento de inventario
                MovimientoInventario::create([
                    'id_producto' => $detalle->id_producto,
                    'tipo_movimiento' => 'entrada',
                    'cantidad' => $detalle->cantidad,
                    'descripcion' => 'Devolución - Pedido #' . $pedido->id_pedido . ' cancelado'
                ]);
            }
            
            // Actualizar estado del pedido
            $pedido->update(['estado' => 'cancelado']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pedido cancelado exitosamente',
                'data' => $pedido
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}
