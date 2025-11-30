<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

/**
 * Controlador del Dashboard Administrativo
 * Maneja las vistas del panel de administración
 */
class DashboardController extends Controller
{
    protected $dashboardService;
    
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    
    /**
     * Página principal del dashboard
     */
    public function index()
    {
        $stats = $this->dashboardService->obtenerEstadisticas();
        $productos_stock_bajo = $this->dashboardService->obtenerProductosStockBajo(10);
        $ultimos_pedidos = $this->dashboardService->obtenerUltimosPedidos(10);
        
        return view('admin.dashboard', compact('stats', 'productos_stock_bajo', 'ultimos_pedidos'));
    }
}
