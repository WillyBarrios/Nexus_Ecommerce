<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Http\Request;

/**
 * Controlador del Dashboard Administrativo
 */
class DashboardController extends Controller
{
    /**
     * Página principal del dashboard
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_productos' => Producto::count(),
            'productos_activos' => Producto::where('estado', 'activo')->count(),
            'total_pedidos' => Pedido::count(),
            'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'total_usuarios' => User::count(),
            'total_categorias' => Categoria::count(),
            'total_marcas' => Marca::count(),
            'ventas_totales' => Pedido::whereIn('estado', ['pagado', 'enviado', 'entregado'])->sum('monto_total'),
        ];
        
        // Productos con stock bajo (menos de 10)
        $productos_stock_bajo = Producto::where('existencia', '<', 10)
                                       ->where('estado', 'activo')
                                       ->with(['categoria', 'marca'])
                                       ->get();
        
        // Últimos pedidos
        $ultimos_pedidos = Pedido::with(['usuario'])
                                ->orderBy('fecha_creacion', 'desc')
                                ->limit(10)
                                ->get();
        
        return view('admin.dashboard', compact('stats', 'productos_stock_bajo', 'ultimos_pedidos'));
    }
}
