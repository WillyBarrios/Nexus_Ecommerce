<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Marca;
use App\Services\ProductoService;
use Illuminate\Http\Request;

/**
 * Controlador Admin de Productos
 * Maneja las vistas del panel de administración
 */
class ProductoAdminController extends Controller
{
    protected $productoService;
    
    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
    }
    
    /**
     * Listar productos
     */
    public function index(Request $request)
    {
        $filtros = $request->only(['buscar', 'categoria', 'estado']);
        $productos = $this->productoService->listarProductos($filtros, 15);
        $categorias = Categoria::all();
        
        return view('admin.productos.index', compact('productos', 'categorias'));
    }
    
    /**
     * Formulario crear producto
     */
    public function create()
    {
        $categorias = Categoria::all();
        $marcas = Marca::all();
        
        return view('admin.productos.create', compact('categorias', 'marcas'));
    }
    
    /**
     * Guardar producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_producto' => 'required|max:150',
            'precio' => 'required|numeric|min:0',
            'existencia' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'id_marca' => 'required|exists:marcas,id_marca',
        ]);
        
        try {
            $this->productoService->crearProducto($request->all());
            
            return redirect()->route('admin.productos.index')
                            ->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Formulario editar producto
     */
    public function edit($id)
    {
        try {
            $producto = $this->productoService->obtenerProducto($id);
            $categorias = Categoria::all();
            $marcas = Marca::all();
            
            return view('admin.productos.edit', compact('producto', 'categorias', 'marcas'));
        } catch (\Exception $e) {
            return redirect()->route('admin.productos.index')
                            ->withErrors(['error' => 'Producto no encontrado']);
        }
    }
    
    /**
     * Actualizar producto
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_producto' => 'required|max:150',
            'precio' => 'required|numeric|min:0',
            'existencia' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'id_marca' => 'required|exists:marcas,id_marca',
        ]);
        
        try {
            $this->productoService->actualizarProducto($id, $request->all());
            
            return redirect()->route('admin.productos.index')
                            ->with('success', 'Producto actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        try {
            $this->productoService->eliminarProducto($id);
            
            return redirect()->route('admin.productos.index')
                            ->with('success', 'Producto eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
