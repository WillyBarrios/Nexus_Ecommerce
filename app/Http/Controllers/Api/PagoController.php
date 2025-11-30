<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador de Pagos
 * 
 * Soporta múltiples métodos de pago:
 * - PayPal
 * - Stripe
 * - Tarjeta (manual)
 * - Efectivo
 * - Transferencia
 */
class PagoController extends Controller
{
    protected $pagoService;
    
    public function __construct(PagoService $pagoService)
    {
        $this->pagoService = $pagoService;
    }
    /**
     * Crear intención de pago
     * POST /api/pagos/crear
     * 
     * Body:
     * {
     *   "id_pedido": 1,
     *   "metodo_pago": "paypal" | "stripe" | "tarjeta" | "efectivo" | "transferencia"
     * }
     */
    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pedido' => 'required|exists:pedidos,id_pedido',
            'metodo_pago' => 'required|in:paypal,stripe,tarjeta,efectivo,transferencia'
        ], [
            'id_pedido.required' => 'El pedido es requerido',
            'id_pedido.exists' => 'El pedido no existe',
            'metodo_pago.required' => 'El método de pago es requerido',
            'metodo_pago.in' => 'Método de pago inválido'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $pago = $this->pagoService->crearPago(
                $request->user()->id_usuario,
                $request->id_pedido,
                $request->metodo_pago
            );
            
            $detallesPago = $this->pagoService->generarDatosPago($pago, $request->metodo_pago);
            
            return response()->json([
                'success' => true,
                'message' => 'Pago creado exitosamente',
                'data' => [
                    'pago' => $pago,
                    'detalles_pago' => $detallesPago
                ]
            ], 201);
            
        } catch (\Exception $e) {
            $statusCode = str_contains($e->getMessage(), 'permiso') ? 403 : 400;
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
    

    
    /**
     * Confirmar pago (webhook o manual)
     * POST /api/pagos/confirmar
     * 
     * Body:
     * {
     *   "id_pago": 1,
     *   "referencia_transaccion": "PAYPAL-123" | "STRIPE-456",
     *   "datos_adicionales": {} // Opcional
     * }
     */
    public function confirmar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pago' => 'required|exists:pagos,id_pago',
            'referencia_transaccion' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $pago = $this->pagoService->confirmarPago(
                $request->id_pago,
                $request->user()->id_usuario,
                $request->referencia_transaccion,
                $request->datos_adicionales ?? []
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Pago confirmado exitosamente',
                'data' => $pago
            ]);
            
        } catch (\Exception $e) {
            $statusCode = str_contains($e->getMessage(), 'permiso') ? 403 : 400;
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
    
    /**
     * Webhook de PayPal
     * POST /api/pagos/paypal/webhook
     */
    public function webhookPayPal(Request $request)
    {
        // NOTA: Aquí iría la verificación del webhook de PayPal
        // Por ahora es un placeholder para que el frontend sepa que existe
        
        return response()->json([
            'success' => true,
            'message' => 'Webhook de PayPal recibido'
        ]);
    }
    
    /**
     * Webhook de Stripe
     * POST /api/pagos/stripe/webhook
     */
    public function webhookStripe(Request $request)
    {
        // NOTA: Aquí iría la verificación del webhook de Stripe
        // Por ahora es un placeholder para que el frontend sepa que existe
        
        return response()->json([
            'success' => true,
            'message' => 'Webhook de Stripe recibido'
        ]);
    }
    
    /**
     * Ver historial de pagos del usuario
     * GET /api/pagos
     */
    public function index(Request $request)
    {
        $pagos = $this->pagoService->listarPagosUsuario($request->user()->id_usuario);
        
        return response()->json([
            'success' => true,
            'data' => $pagos->items(),
            'pagination' => [
                'total' => $pagos->total(),
                'per_page' => $pagos->perPage(),
                'current_page' => $pagos->currentPage(),
                'last_page' => $pagos->lastPage(),
            ]
        ]);
    }
    
    /**
     * Ver detalle de un pago
     * GET /api/pagos/{id}
     */
    public function show(Request $request, $id)
    {
        try {
            $pago = $this->pagoService->obtenerPago($id, $request->user());
            
            return response()->json([
                'success' => true,
                'data' => $pago
            ]);
        } catch (\Exception $e) {
            $statusCode = str_contains($e->getMessage(), 'permiso') ? 403 : 404;
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
}
