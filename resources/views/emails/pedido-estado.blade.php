<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Pedido</title>
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
            background-color: #FF9800;
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
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
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
        <h1>Actualización de Pedido</h1>
        <p>Número de Pedido: {{ $pedido->numero_pedido }}</p>
    </div>
    
    <div class="content">
        <h2>Hola {{ $usuario->nombre_completo }},</h2>
        
        <p>El estado de tu pedido ha sido actualizado.</p>
        
        <p><strong>Estado anterior:</strong> {{ ucfirst($estadoAnterior) }}</p>
        <p><strong>Estado actual:</strong> <span class="status-badge">{{ ucfirst($pedido->estado) }}</span></p>
        
        @if($pedido->estado == 'enviado')
        <p>Tu pedido ha sido enviado y está en camino. Recibirás tu paquete pronto.</p>
        @elseif($pedido->estado == 'entregado')
        <p>Tu pedido ha sido entregado. Esperamos que disfrutes tu compra.</p>
        @elseif($pedido->estado == 'cancelado')
        <p>Tu pedido ha sido cancelado. Si tienes alguna pregunta, contáctanos.</p>
        @endif
        
        <p><strong>Monto total:</strong> ${{ number_format($pedido->monto_total, 2) }}</p>
        
        <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Nexus E-commerce. Todos los derechos reservados.</p>
    </div>
</body>
</html>
