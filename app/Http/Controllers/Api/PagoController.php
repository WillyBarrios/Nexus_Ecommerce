<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        
        // Verificar que el pedido existe y pertenece al usuario
        $pedido = Pedido::find($request->id_pedido);
        
        if ($pedido->id_usuario != $request->user()->id_usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para pagar este pedido'
            ], 403);
        }
        
        // Verificar que el pedido no tenga ya un pago completado
        $pagoExistente = Pago::where('id_pedido', $pedido->id_pedido)
                            ->where('estado', 'completado')
                            ->first();
        
        if ($pagoExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Este pedido ya ha sido pagado'
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            // Crear registro de pago
            $pago = Pago::create([
                'id_usuario' => $request->user()->id_usuario,
                'metodo_pago' => $request->metodo_pago,
                'monto' => $pedido->monto_total,
                'estado' => 'pendiente'
            ]);
            
            // Actualizar pedido con el ID del pago
            $pedido->update(['id_pago' => $pago->id_pago]);
            
            // Generar respuesta según el método de pago
            $respuesta = $this->generarRespuestaPago($pago, $request->metodo_pago);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pago creado exitosamente',
                'data' => [
                    'pago' => $pago,
                    'detalles_pago' => $respuesta
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el pago: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generar respuesta según método de pago
     */
    private function generarRespuestaPago($pago, $metodo)
    {
        switch ($metodo) {
            case 'paypal':
                return $this->generarPayPal($pago);
            
            case 'stripe':
                return $this->generarStripe($pago);
            
            case 'tarjeta':
            case 'efectivo':
            case 'transferencia':
                return [
                    'tipo' => $metodo,
                    'instrucciones' => $this->obtenerInstrucciones($metodo),
                    'referencia' => 'PAY-' . $pago->id_pago . '-' . time()
                ];
            
            default:
                return ['tipo' => 'desconocido'];
        }
    }
    
    /**
     * Generar datos para PayPal
     */
    private function generarPayPal($pago)
    {
        // NOTA: Aquí iría la integración real con PayPal SDK
        // Por ahora retornamos datos de ejemplo para que el frontend pueda implementar
        
        return [
            'tipo' => 'paypal',
            'client_id' => env('PAYPAL_CLIENT_ID', 'TU_PAYPAL_CLIENT_ID'),
            'amount' => $pago->monto,
            'currency' => 'USD',
            'description' => 'Pedido #' . $pago->id_pedido,
            'return_url' => url('/api/pagos/paypal/success'),
            'cancel_url' => url('/api/pagos/paypal/cancel'),
            'webhook_url' => url('/api/pagos/paypal/webhook'),
            
            // Datos para el frontend
            'instrucciones' => [
                '1. El frontend debe cargar el SDK de PayPal',
                '2. Usar el client_id proporcionado',
                '3. Crear el botón de PayPal con el monto',
                '4. Al completar, llamar a /api/pagos/confirmar con el payment_id'
            ],
            
            // Ejemplo de integración
            'ejemplo_frontend' => [
                'sdk_url' => 'https://www.paypal.com/sdk/js?client-id=' . env('PAYPAL_CLIENT_ID', 'TU_CLIENT_ID'),
                'metodo' => 'paypal.Buttons().render("#paypal-button-container")'
            ]
        ];
    }
    
    /**
     * Generar datos para Stripe
     */
    private function generarStripe($pago)
    {
        // NOTA: Aquí iría la integración real con Stripe SDK
        // Por ahora retornamos datos de ejemplo para que el frontend pueda implementar
        
        return [
            'tipo' => 'stripe',
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY', 'TU_STRIPE_PUBLISHABLE_KEY'),
            'amount' => $pago->monto * 100, // Stripe usa centavos
            'currency' => 'usd',
            'description' => 'Pedido #' . $pago->id_pedido,
            'success_url' => url('/api/pagos/stripe/success'),
            'cancel_url' => url('/api/pagos/stripe/cancel'),
            'webhook_url' => url('/api/pagos/stripe/webhook'),
            
            // Datos para el frontend
            'instrucciones' => [
                '1. El frontend debe cargar Stripe.js',
                '2. Usar la publishable_key proporcionada',
                '3. Crear el formulario de pago con Stripe Elements',
                '4. Al completar, llamar a /api/pagos/confirmar con el payment_intent_id'
            ],
            
            // Ejemplo de integración
            'ejemplo_frontend' => [
                'sdk_url' => 'https://js.stripe.com/v3/',
                'metodo' => 'stripe.confirmCardPayment(clientSecret)'
            ]
        ];
    }
    
    /**
     * Obtener instrucciones para métodos manuales
     */
    private function obtenerInstrucciones($metodo)
    {
        $instrucciones = [
            'tarjeta' => 'Proporciona los datos de tu tarjeta para procesar el pago',
            'efectivo' => 'Realiza el pago en efectivo al recibir tu pedido',
            'transferencia' => 'Realiza una transferencia bancaria a la cuenta proporcionada'
        ];
        
        return $instrucciones[$metodo] ?? 'Sigue las instrucciones para completar el pago';
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
        
        $pago = Pago::find($request->id_pago);
        
        // Verificar que el pago pertenece al usuario
        if ($pago->id_usuario != $request->user()->id_usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para confirmar este pago'
            ], 403);
        }
        
        DB::beginTransaction();
        
        try {
            // Actualizar pago
            $pago->update([
                'estado' => 'completado',
                'referencia_transaccion' => $request->referencia_transaccion,
                'datos_pago' => $request->datos_adicionales ?? []
            ]);
            
            // Actualizar estado del pedido
            $pago->pedido->update(['estado' => 'pagado']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pago confirmado exitosamente',
                'data' => $pago
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar el pago: ' . $e->getMessage()
            ], 500);
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
        $pagos = Pago::where('id_usuario', $request->user()->id_usuario)
                    ->with(['pedido'])
                    ->orderBy('fecha_creacion', 'desc')
                    ->paginate(15);
        
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
        $pago = Pago::with(['pedido', 'usuario'])->find($id);
        
        if (!$pago) {
            return response()->json([
                'success' => false,
                'message' => 'Pago no encontrado'
            ], 404);
        }
        
        // Verificar permisos
        if ($pago->id_usuario != $request->user()->id_usuario && $request->user()->id_rol != 1) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver este pago'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'data' => $pago
        ]);
    }
}
