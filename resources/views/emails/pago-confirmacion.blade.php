<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago</title>
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
            background-color: #4CAF50;
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
        .payment-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            margin: 20px 0;
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
        <h1>Pago Confirmado</h1>
    </div>
    
    <div class="content">
        <h2>Hola {{ $usuario->nombre_completo }},</h2>
        
        <p>Hemos recibido tu pago exitosamente.</p>
        
        <div class="payment-details">
            <h3>Detalles del Pago</h3>
            
            <p><strong>Método de pago:</strong> {{ ucfirst($pago->metodo_pago) }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($pago->estado) }}</p>
            <p><strong>Fecha:</strong> {{ $pago->fecha_pago->format('d/m/Y H:i') }}</p>
            
            @if($pago->referencia_transaccion)
            <p><strong>Referencia:</strong> {{ $pago->referencia_transaccion }}</p>
            @endif
            
            <div class="amount">
                Monto: ${{ number_format($pago->monto, 2) }}
            </div>
        </div>
        
        @if($pago->pedido)
        <p><strong>Pedido asociado:</strong> {{ $pago->pedido->numero_pedido }}</p>
        @endif
        
        <p>Gracias por tu compra. Tu pedido será procesado pronto.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Nexus E-commerce. Todos los derechos reservados.</p>
    </div>
</body>
</html>
