<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PedidoService;
use Illuminate\Http\Request;

/**
 * Controlador Admin de Pedidos
 * Maneja las vistas del panel de administración
 */
class PedidoAdminController extends Controller
{
    protected $pedidoService;
    
    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }
    
    /**
     * Listar todos los pedidos
     */
    public function index(Request $request)
    {
        $filtros = $request->only(['estado']);
        $usuario = $request->user() ?? auth()->user();
        $pedidos = $this->pedidoService->listarPedidos($usuario, $filtros);
        
        return view('admin.pedidos.index', compact('pedidos'));
    }

    /**
     * Ver detalle del pedido
     */
    public function show(Request $request, $id)
    {
        try {
            $usuario = $request->user() ?? auth()->user();
            $pedido = $this->pedidoService->obtenerPedido($id, $usuario);
            return view('admin.pedidos.show', compact('pedido'));
        } catch (\Exception $e) {
            return redirect()->route('admin.pedidos.index')
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Actualizar estado del pedido
     */
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,pagado,enviado,entregado,cancelado'
        ]);

        try {
            $this->pedidoService->actualizarEstado($id, $request->estado);

            return redirect()->back()
                ->with('success', 'Estado del pedido actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
