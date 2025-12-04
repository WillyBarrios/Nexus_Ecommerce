<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    protected $productoService;
    
    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
    }
    
    public function index(Request $request)
    {
        $filtros = $request->only(['buscar', 'categoria', 'marca', 'precio_min', 'precio_max', 'estado', 'orden']);
        $perPage = $request->get('per_page', 15);
        
        $productos = $this->productoService->listarProductos($filtros, $perPage);
        
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
    
    public function show($id)
    {
        try {
            $producto = $this->productoService->obtenerProducto($id);
            
            return response()->json([
                'success' => true,
                'data' => $producto
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }
    
    public function store(Request $request)
    {
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
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $producto = $this->productoService->crearProducto($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'data' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function update(Request $request, $id)
    {
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
        
        try {
            $producto = $this->productoService->actualizarProducto($id, $request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
    
    public function destroy($id)
    {
        try {
            $this->productoService->eliminarProducto($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }
    
    public function agregarImagen(Request $request, $id)
    {
        // Validar que se envió una imagen o una URL
        $validator = Validator::make($request->all(), [
            'imagen' => 'required_without:url_imagen|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url_imagen' => 'required_without:imagen|url'
        ], [
            'imagen.required_without' => 'Debe proporcionar una imagen o una URL',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp',
            'imagen.max' => 'La imagen no debe ser mayor a 2MB',
            'url_imagen.required_without' => 'Debe proporcionar una URL o una imagen',
            'url_imagen.url' => 'La URL no es válida'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $urlImagen = null;
            
            // Si se subió un archivo, guardarlo
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreArchivo = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = $imagen->storeAs('productos', $nombreArchivo, 'public');
                $urlImagen = asset('storage/' . $ruta);
            } 
            // Si se proporcionó una URL, usarla directamente
            else if ($request->has('url_imagen')) {
                $urlImagen = $request->url_imagen;
            }
            
            $imagen = $this->productoService->agregarImagen($id, $urlImagen);
            
            return response()->json([
                'success' => true,
                'message' => 'Imagen agregada exitosamente',
                'data' => $imagen
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
    
    public function eliminarImagen($id_imagen)
    {
        try {
            $this->productoService->eliminarImagen($id_imagen);
            
            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
