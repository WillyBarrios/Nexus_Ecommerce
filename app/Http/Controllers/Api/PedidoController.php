<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PedidoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    protected $pedidoService;
    
    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }
    
    public function index(Request $request)
    {
        $filtros = $request->only(['estado', 'usuario_id']);
        $pedidos = $this->pedidoService->listarPedidos($request->user(), $filtros);
        
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
    
    public function show(Request $request, $id)
    {
        try {
            $pedido = $this->pedidoService->obtenerPedido($id, $request->user());
            
            return response()->json([
                'success' => true,
                'data' => $pedido
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getMessage() === 'No tienes permiso para ver este pedido' ? 403 : 404;
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
    
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
        
        try {
            $pedido = $this->pedidoService->crearPedido($request->user()->id_usuario, $request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Pedido creado exitosamente',
                'data' => $pedido
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function actualizarEstado(Request $request, $id)
    {
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
        
        try {
            $pedido = $this->pedidoService->actualizarEstado($id, $request->estado);
            
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'data' => $pedido
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        }
    }
    
    public function cancelar(Request $request, $id)
    {
        try {
            $pedido = $this->pedidoService->cancelarPedido($id, $request->user());
            
            return response()->json([
                'success' => true,
                'message' => 'Pedido cancelado exitosamente',
                'data' => $pedido
            ]);
        } catch (\Exception $e) {
            $statusCode = str_contains($e->getMessage(), 'permiso') ? 403 : 400;
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
}
