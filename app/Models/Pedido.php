<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Pedido
 * 
 * Representa una orden de compra realizada por un usuario.
 * Contiene información del pedido y su estado.
 */
class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    
    protected $fillable = [
        'id_usuario',
        'numero_pedido',
        'monto_total',
        'estado',
        'id_pago'
    ];
    
    protected $casts = [
        'monto_total' => 'decimal:2',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];
    
    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
    
    /**
     * Relación con Detalles del Pedido
     */
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }
    
    /**
     * Relación con Pagos
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_pedido', 'id_pedido');
    }
    
    /**
     * Scopes para filtrar por estado
     */
    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }
    
    public function scopePagado($query)
    {
        return $query->where('estado', 'pagado');
    }
    
    public function scopeEnviado($query)
    {
        return $query->where('estado', 'enviado');
    }
    
    public function scopeEntregado($query)
    {
        return $query->where('estado', 'entregado');
    }
    
    public function scopeCancelado($query)
    {
        return $query->where('estado', 'cancelado');
    }
    
    /**
     * Verificar si el pedido puede ser cancelado
     */
    public function puedeCancelarse()
    {
        return in_array($this->estado, ['pendiente', 'pagado']);
    }
    
    /**
     * Calcular total del pedido
     */
    public function calcularTotal()
    {
        $total = 0;
        foreach ($this->detalles as $detalle) {
            $total += $detalle->precio_unitario * $detalle->cantidad;
        }
        return $total;
    }
    
    /**
     * Serialización a JSON
     */
    public function toArray()
    {
        // Obtener el primer pago si existe
        $pago = $this->pagos->first();
        
        return [
            'id_pedido' => $this->id_pedido,
            'numero_pedido' => $this->numero_pedido,
            'usuario' => [
                'id_usuario' => $this->usuario->id_usuario,
                'nombre_completo' => $this->usuario->nombre_completo,
                'correo_electronico' => $this->usuario->correo_electronico,
            ],
            'detalles' => $this->detalles->map(function($detalle) {
                return [
                    'id_detalle_pedido' => $detalle->id_detalle_pedido,
                    'producto' => [
                        'id_producto' => $detalle->producto->id_producto,
                        'nombre' => $detalle->producto->nombre_producto,
                    ],
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => (float) $detalle->precio_unitario,
                    'subtotal' => (float) $detalle->subtotal
                ];
            }),
            'monto_total' => (float) $this->monto_total,
            'estado' => $this->estado,
            'pago' => $pago ? [
                'id_pago' => $pago->id_pago,
                'metodo_pago' => $pago->metodo_pago,
                'estado' => $pago->estado,
            ] : null,
            'direccion_envio' => $this->direccion_envio,
            'telefono_contacto' => $this->telefono_contacto,
            'notas' => $this->notas,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_actualizacion' => $this->fecha_actualizacion,
        ];
    }
}
