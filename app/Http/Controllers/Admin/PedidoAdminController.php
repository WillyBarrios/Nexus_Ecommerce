<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

/**
 * Controlador Admin de Pedidos
 */
class PedidoAdminController extends Controller
{
    /**
     * Listar todos los pedidos
     */
    public function index(Request $request)
    {
        $query = Pedido::with(['usuario']);

        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $pedidos = $query->orderBy('fecha_creacion', 'desc')->paginate(15);
        
        return view('admin.pedidos.index', compact('pedidos'));
    }

    /**
     * Ver detalle del pedido
     */
    public function show($id)
    {
        $pedido = Pedido::with(['usuario', 'detalles.producto'])->findOrFail($id);
        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Actualizar estado del pedido
     */
    public function updateEstado(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);
        
        $request->validate([
            'estado' => 'required|in:pendiente,pagado,enviado,entregado,cancelado'
        ]);

        $pedido->update(['estado' => $request->estado]);

        return redirect()->back()
            ->with('success', 'Estado del pedido actualizado exitosamente');
    }
}
