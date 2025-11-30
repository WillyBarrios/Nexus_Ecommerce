<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

/**
 * Controlador Admin de Productos
 */
class ProductoAdminController extends Controller
{
    /**
     * Listar productos
     */
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'marca']);
        
        // Búsqueda
        if ($request->has('buscar')) {
            $query->where('nombre_producto', 'like', '%' . $request->buscar . '%');
        }
        
        // Filtro por categoría
        if ($request->has('categoria')) {
            $query->where('id_categoria', $request->categoria);
        }
        
        // Filtro por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $productos = $query->paginate(15);
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
        
        $producto = Producto::create($request->all());
        
        // Registrar movimiento de inventario
        MovimientoInventario::create([
            'id_producto' => $producto->id_producto,
            'tipo_movimiento' => 'entrada',
            'cantidad' => $request->existencia,
            'descripcion' => 'Stock inicial'
        ]);
        
        return redirect()->route('admin.productos.index')
                        ->with('success', 'Producto creado exitosamente');
    }
    
    /**
     * Formulario editar producto
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        $marcas = Marca::all();
        
        return view('admin.productos.edit', compact('producto', 'categorias', 'marcas'));
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
        
        $producto = Producto::findOrFail($id);
        $existenciaAnterior = $producto->existencia;
        
        $producto->update($request->all());
        
        // Si cambió la existencia, registrar movimiento
        if ($request->existencia != $existenciaAnterior) {
            $diferencia = $request->existencia - $existenciaAnterior;
            MovimientoInventario::create([
                'id_producto' => $producto->id_producto,
                'tipo_movimiento' => $diferencia > 0 ? 'entrada' : 'salida',
                'cantidad' => abs($diferencia),
                'descripcion' => 'Ajuste manual de inventario'
            ]);
        }
        
        return redirect()->route('admin.productos.index')
                        ->with('success', 'Producto actualizado exitosamente');
    }
    
    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update(['estado' => 'inactivo']);
        
        return redirect()->route('admin.productos.index')
                        ->with('success', 'Producto eliminado exitosamente');
    }
}
