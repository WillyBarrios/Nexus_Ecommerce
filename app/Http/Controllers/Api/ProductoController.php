<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador API de Productos
 * 
 * Maneja todas las operaciones CRUD de productos para la API REST
 */
class ProductoController extends Controller
{
    /**
     * Listar todos los productos
     * GET /api/productos
     * 
     * Parámetros opcionales:
     * - buscar: término de búsqueda
     * - categoria: filtrar por categoría
     * - marca: filtrar por marca
     * - precio_min: precio mínimo
     * - precio_max: precio máximo
     * - estado: activo/inactivo
     * - orden: precio_asc, precio_desc, nombre, recientes
     * - per_page: cantidad por página (default: 15)
     */
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'marca', 'imagenes']);
        
        // Búsqueda por nombre o descripción
        if ($request->has('buscar')) {
            $query->buscar($request->buscar);
        }
        
        // Filtro por categoría
        if ($request->has('categoria')) {
            $query->where('id_categoria', $request->categoria);
        }
        
        // Filtro por marca
        if ($request->has('marca')) {
            $query->where('id_marca', $request->marca);
        }
        
        // Filtro por rango de precio
        if ($request->has('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        if ($request->has('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }
        
        // Filtro por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        } else {
            // Por defecto solo mostrar activos
            $query->activos();
        }
        
        // Ordenamiento
        switch ($request->get('orden', 'recientes')) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre_producto', 'asc');
                break;
            case 'recientes':
            default:
                $query->orderBy('fecha_creacion', 'desc');
                break;
        }
        
        // Paginación
        $perPage = $request->get('per_page', 15);
        $productos = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $productos->items(),
            'pagination' => [
                'total' => $productos->total(),
                'per_page' => $productos->perPage(),
                'current_page' => $productos->currentPage(),
                'last_page' => $productos->lastPage(),
                'from' => $productos->firstItem(),
                'to' => $productos->lastItem()
            ]
        ]);
    }
    
    /**
     * Ver detalle de un producto
     * GET /api/productos/{id}
     */
    public function show($id)
    {
        $producto = Producto::with(['categoria', 'marca', 'imagenes'])
                           ->find($id);
        
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $producto
        ]);
    }
    
    /**
     * Crear un nuevo producto
     * POST /api/productos
     * 
     * Requiere autenticación y rol de Admin
     */
    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'nombre_producto' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'existencia' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'id_marca' => 'required|exists:marcas,id_marca',
            'estado' => 'in:activo,inactivo',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'url'
        ], [
            'nombre_producto.required' => 'El nombre del producto es requerido',
            'precio.required' => 'El precio es requerido',
            'precio.numeric' => 'El precio debe ser un número',
            'precio.min' => 'El precio no puede ser negativo',
            'existencia.required' => 'La existencia es requerida',
            'existencia.integer' => 'La existencia debe ser un número entero',
            'id_categoria.required' => 'La categoría es requerida',
            'id_categoria.exists' => 'La categoría seleccionada no existe',
            'id_marca.required' => 'La marca es requerida',
            'id_marca.exists' => 'La marca seleccionada no existe',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Crear producto
        $producto = Producto::create([
            'nombre_producto' => $request->nombre_producto,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'existencia' => $request->existencia,
            'id_categoria' => $request->id_categoria,
            'id_marca' => $request->id_marca,
            'estado' => $request->get('estado', 'activo')
        ]);
        
        // Agregar imágenes si se proporcionaron
        if ($request->has('imagenes')) {
            foreach ($request->imagenes as $url) {
                $producto->imagenes()->create([
                    'url_imagen' => $url
                ]);
            }
        }
        
        // Registrar movimiento de inventario (entrada inicial)
        MovimientoInventario::create([
            'id_producto' => $producto->id_producto,
            'tipo_movimiento' => 'entrada',
            'cantidad' => $request->existencia,
            'descripcion' => 'Stock inicial del producto'
        ]);
        
        // Cargar relaciones
        $producto->load(['categoria', 'marca', 'imagenes']);
        
        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente',
            'data' => $producto
        ], 201);
    }
    
    /**
     * Actualizar un producto
     * PUT /api/productos/{id}
     * 
     * Requiere autenticación y rol de Admin
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
        
        // Validación
        $validator = Validator::make($request->all(), [
            'nombre_producto' => 'string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'numeric|min:0',
            'existencia' => 'integer|min:0',
            'id_categoria' => 'exists:categorias,id_categoria',
            'id_marca' => 'exists:marcas,id_marca',
            'estado' => 'in:activo,inactivo'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Guardar existencia anterior para registrar movimiento
        $existenciaAnterior = $producto->existencia;
        
        // Actualizar producto
        $producto->update($request->only([
            'nombre_producto',
            'descripcion',
            'precio',
            'existencia',
            'id_categoria',
            'id_marca',
            'estado'
        ]));
        
        // Si cambió la existencia, registrar movimiento
        if ($request->has('existencia') && $request->existencia != $existenciaAnterior) {
            $diferencia = $request->existencia - $existenciaAnterior;
            MovimientoInventario::create([
                'id_producto' => $producto->id_producto,
                'tipo_movimiento' => $diferencia > 0 ? 'entrada' : 'salida',
                'cantidad' => abs($diferencia),
                'descripcion' => 'Ajuste de inventario'
            ]);
        }
        
        // Cargar relaciones
        $producto->load(['categoria', 'marca', 'imagenes']);
        
        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado exitosamente',
            'data' => $producto
        ]);
    }
    
    /**
     * Eliminar un producto (soft delete - cambiar estado a inactivo)
     * DELETE /api/productos/{id}
     * 
     * Requiere autenticación y rol de Admin
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
        
        // Cambiar estado a inactivo en lugar de eliminar
        $producto->update(['estado' => 'inactivo']);
        
        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente'
        ]);
    }
    
    /**
     * Agregar imagen a un producto
     * POST /api/productos/{id}/imagenes
     */
    public function agregarImagen(Request $request, $id)
    {
        $producto = Producto::find($id);
        
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'url_imagen' => 'required|url'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $imagen = $producto->imagenes()->create([
            'url_imagen' => $request->url_imagen
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Imagen agregada exitosamente',
            'data' => $imagen
        ], 201);
    }
    
    /**
     * Eliminar imagen de un producto
     * DELETE /api/productos/imagenes/{id_imagen}
     */
    public function eliminarImagen($id_imagen)
    {
        $imagen = \App\Models\ImagenProducto::find($id_imagen);
        
        if (!$imagen) {
            return response()->json([
                'success' => false,
                'message' => 'Imagen no encontrada'
            ], 404);
        }
        
        $imagen->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Imagen eliminada exitosamente'
        ]);
    }
}
