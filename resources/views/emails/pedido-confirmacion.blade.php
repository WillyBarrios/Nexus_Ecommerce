<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2196F3;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .order-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .product-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            color: #2196F3;
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pedido Confirmado</h1>
        <p>Número de Pedido: {{ $pedido->numero_pedido }}</p>
    </div>
    
    <div class="content">
        <h2>Hola {{ $usuario->nombre_completo }},</h2>
        
        <p>Tu pedido ha sido recibido y está siendo procesado.</p>
        
        <div class="order-details">
            <h3>Detalles del Pedido</h3>
            
            @foreach($pedido->detalles as $detalle)
            <div class="product-item">
                <strong>{{ $detalle->producto->nombre_producto }}</strong><br>
                Cantidad: {{ $detalle->cantidad }} x ${{ number_format($detalle->precio_unitario, 2) }}
                = ${{ number_format($detalle->subtotal, 2) }}
            </div>
            @endforeach
            
            <div class="total">
                Total: ${{ number_format($pedido->monto_total, 2) }}
            </div>
        </div>
        
        <p><strong>Dirección de envío:</strong><br>
        {{ $pedido->direccion_envio }}</p>
        
        <p><strong>Teléfono de contacto:</strong><br>
        {{ $pedido->telefono_contacto }}</p>
        
        @if($pedido->notas)
        <p><strong>Notas:</strong><br>
        {{ $pedido->notas }}</p>
        @endif
        
        <p>Te notificaremos cuando tu pedido sea enviado.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Nexus E-commerce. Todos los derechos reservados.</p>
    </div>
</body>
</html>
